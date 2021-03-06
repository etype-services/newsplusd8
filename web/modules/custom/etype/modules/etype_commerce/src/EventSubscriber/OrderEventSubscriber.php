<?php

namespace Drupal\etype_commerce\EventSubscriber;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use League\Csv\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;
use Drupal\commerce_cart\Event\CartEntityAddEvent;
use MailchimpMarketing\ApiClient;
use MailchimpMarketing\ApiException;

/**
 * Class OrderEventSubscriber adds Events on Order Completion.
 *
 * See
 * https://docs.drupalcommerce.org/commerce2/developer-guide/adapting-from-1x/commerce-without-rules.
 *
 * @package Drupal\etype_commerce\EventSubscriber
 */
class OrderEventSubscriber implements EventSubscriberInterface
{

  /**
   * Config.
   *
   * @var OrderEventSubscriber
   */
  protected $conf;

  /**
   * Success or failure message.
   *
   * @var string
   */
  protected $message;

  /**
   * User role.
   *
   * @var string
   */
  protected $role;

  /**
   * The Order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $order;

  /**
   * The User.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * OrderEventSubscriber constructor.
   */
  public function __construct() {

    $this->message = '';
    $this->role = '';
    $this->conf = \Drupal::config('etype_commerce.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      'commerce_order.place.post_transition' => ['onPlaceTransition'],
      'commerce_cart.entity.add' => ['onCartEntityAdd'],
    ];
  }

  /**
   * Updates User when an order is placed.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   *   The transition event.
   *
   * @throws \Exception
   */
  public function onPlaceTransition(WorkflowTransitionEvent $event) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();
    $this->order = $order;
    $order_id = $this->order->id();
    $customer_id = $this->order->getCustomerId();
    /* Attempt to stop oddness where role added to uid 0 */
    if ($customer_id > 0) {
      /* Is this a gift? */
      $query = \Drupal::entityQuery('gift_subscription')
        ->condition('order_id', $order_id);
      $ids = $query->execute();
      if (count($ids) > 0) {
        /* This is a gift. There should only be one entity matching order id */
        $query = \Drupal::entityQuery('gift_subscription')
          ->condition('order_id', $order_id);
        $ids = $query->execute();
        $entity = \Drupal::entityTypeManager()
          ->getStorage('gift_subscription')
          ->load(reset($ids));
        $email = $entity->get('email')->getValue();
        $gift_email = $email[0]['value'];
        $check = user_load_by_mail($gift_email);
        if ($check == FALSE) {
          /* Send email to giftee. */
          $user = User::load($customer_id);
          $config = \Drupal::config('system.site');
          $host = \Drupal::request()->getSchemeAndHttpHost();
          $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
          $params = [
            'site_name' => $config->get('name'),
            'site_url' => $host,
            'gifter' => $user->getUserName(),
            'orderId' => $order_id,
          ];
          \Drupal::service('plugin.manager.mail')
            ->mail('etype_commerce', 'gift_subscription', $gift_email, $language, $params);
        }
        else {
          /* Gift user exists - extend subscription */
          $this->extendSubscription($check->id(), 1);
        }
        /* Mark gift sub as Paid */
        $entity->set("paid", 1);
        $entity->save();
      }
      else {
        /*
         * Not a gift.
         * Extend subscription by 1 year from today if Sub Expiry is in past,
         * or 1 year from Sub Expiry.
         */
        $this->extendSubscription($customer_id);
      }
    }
  }

  /**
   * Extends User Subscription.
   *
   * @param int $uid
   *   Customer/User Id.
   * @param int|null $gift
   *   Flag for Gift subscription.
   *
   * @throws \Exception
   */
  public function extendSubscription(int $uid, int $gift = NULL) {
    $this->user = User::load($uid);
    $username = $this->user->getUsername();
    $email = $this->user->getEmail();
    $formatted_duration = '';
    $formatted_role = '';
    $subExpiry = '';

    /* Initialize  DateTime object. */
    $myDateTime = new \DateTime();
    /* Set variable with new subscription date, today. */
    $subDate = $myDateTime->format('Y-m-d');
    /* Get current sub expiry date. */
    $field_subscription_expiry = $this->user->get('field_subscription_expiry')
      ->getValue();

    /*
     * Loop over order items and process subscription.
     * There should only be one item
     */
    foreach ($this->order->getItems() as $key => $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $arr = $product_variation->get('attribute_subscription_type')->getValue();
      $target_id = ($arr[0]["target_id"]);
      try {
        $entity = \Drupal::entityTypeManager()
          ->getStorage('commerce_product_attribute')
          ->load('subscription_type')
          ->getValues();
        $arr2 = $entity[$target_id]->name->getValue();
        $this->role = $arr2[0]['value'];
        switch ($this->role) {
          case 'Print & Digital';
            $formatted_role = 'print_digital_subscriber';
            break;

          default:
            $formatted_role = 'digital_subscriber';
        }
      }
      catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
        \Drupal::messenger()->addError('Could not load commerce_product_attribute entity');
      }

      $arr = $product_variation->get('attribute_duration')->getValue();
      $target_id = ($arr[0]["target_id"]);
      try {
        $entity = \Drupal::entityTypeManager()
          ->getStorage('commerce_product_attribute')
          ->load('duration')
          ->getValues();
        $arr2 = $entity[$target_id]->name->getValue();
        $duration = $arr2[0]['value'];
        switch ($duration) {
          case '1 year';
            $formatted_duration = 'P1Y';
            break;

          case '6 months';
            $formatted_duration = 'P6M';
            break;

          default:
            $formatted_duration = 'P3D';
        }
      }
      catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
        \Drupal::messenger()->addError('Could not load commerce_product_attribute entity');
      }

      /* Is the Sub expiry date in the past, in the future, or does it exist? */
      if (isset($field_subscription_expiry[0]['value'])) {
        try {
          $oldSubExpiry = new \DateTime($field_subscription_expiry[0]['value']);
          $days = $myDateTime->diff($oldSubExpiry)->format('%R%a');
          if ($days > 0) {
            $subExpiry = $oldSubExpiry->add(new \DateInterval($formatted_duration))
              ->format('Y-m-d');
          }
          else {
            $subExpiry = $myDateTime->add(new \DateInterval($formatted_duration))
              ->format('Y-m-d');
          }
        }
        catch (\Exception $e) {
          \Drupal::messenger()->addError('Could not create DateTime object');
        }
      }
      else {
        $subExpiry = $myDateTime->add(new \DateInterval($formatted_duration))
          ->format('Y-m-d');
      }

      $this->user->addRole($formatted_role);
      $this->user->set('field_subscription_date', $subDate);
      $this->user->set('field_subscription_expiry', $subExpiry);
    }

    try {
      if (!$this->user->save()) {
        throw new Exception("Unable to save user.");
      }
      switch ($gift) {
        case 1:
          $this->message .= "You’ve successfully purchased a gift subscription for $email. They will receive an email inviting them to comfirm the subscription.";
          break;

        default:
          $this->message .= "Hello $username, you are logged in, and your subscription is now valid through $subExpiry";
      }

      \Drupal::messenger()->addMessage($this->message);
    }
    catch (Exception | EntityStorageException $e) {
      echo 'Caught Exception: ', $e->getMessage(), "\n";
      exit;
    }

    /* Add subscriber to MailChimp */
    $this->addToMailChimp($email);

  }

  /**
   * Actions when an entity has been added to the cart.
   *
   * Removes more than 1 item from cart.
   *
   * @param \Drupal\commerce_cart\Event\CartEntityAddEvent $event
   *   The event.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * @see https://drupal.stackexchange.com/questions/271489/commerce-limit-the-quantity-of-product-to-1
   */
  public function onCartEntityAdd(CartEntityAddEvent $event) {
    $cart = $event->getCart();
    $added_order_item = $event->getOrderItem();
    $cart_items = $cart->getItems();
    foreach ($cart_items as $cart_item) {
      if ($cart_item->id() != $added_order_item->id()) {
        $cart->removeItem($cart_item);
        $cart_item->delete();
      }
    }
    $quantity = $cart_items[0]->getQuantity();
    if ($quantity > 1) {
      $cart_items[0]->setQuantity(1);
    }
    $cart->save();
  }

  /**
   * Add subscriber to MailChimp.
   *
   * @param string $email
   *   Email Address to add to MailChimp.
   */
  public function addToMailChimp(string $email) {

    $mailchimp = new ApiClient();
    $mailchimp->setConfig([
      'apiKey' => $this->conf->get('MailChimpAPIKey'),
      'server' => $this->conf->get('MailChimpServerPrefix'),
    ]);
    try {
      $mailchimp->ping->get();
    }
    catch (ApiException $e) {
      echo $e->getMessage();
    }
    $list_id = $this->conf->get('MailChimpListId');
    $subscriber_hash = md5($email);
    try {
      $mailchimp->lists->setListMember($list_id, $subscriber_hash, [
        "email_address" => $email,
        "status_if_new" => "subscribed",
      ]);
    }
    catch (ApiException $e) {
      echo $e->getMessage();
    }
  }

}
