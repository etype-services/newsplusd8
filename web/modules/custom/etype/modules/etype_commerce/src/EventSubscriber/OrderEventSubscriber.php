<?php

namespace Drupal\etype_commerce\EventSubscriber;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use League\Csv\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;
use Drupal\commerce_cart\Event\CartEntityAddEvent;

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
   * Success or failure message.
   *
   * @var string
   */
  protected string $message;

  /**
   * User role.
   *
   * @var string
   */
  protected string $role;

  /**
   * The Order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected OrderInterface $order;

  /**
   * OrderEventSubscriber constructor.
   */
  public function __construct() {
    $this->message = '';
    $this->role = '';
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

    /* Is this a gift? */
    $query = \Drupal::entityQuery('gift_subscription')
      ->condition('order_id', $order_id);
    $ids = $query->execute();
    if (count($ids) > 0) {
      /* This is a gift. There should only be one entity matching order id */
      /* TODO: send email to gift subscriber */
      $query = \Drupal::entityQuery('gift_subscription')
        ->condition('order_id', $order_id);
      $ids = $query->execute();
      $entity = \Drupal::entityTypeManager()
        ->getStorage('gift_subscription')
        ->load(reset($ids));
      $email = $entity->get('email')->getValue();
      $gift_email = $email['value'];
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

  /**
   * Extends User Subscription.
   *
   * @param int $uid
   *   Customer Id.
   *
   * @throws \Exception
   */
  public function extendSubscription(int $uid) {
    $user = User::load($uid);
    $username = $user->getUsername();
    $formatted_duration = '';
    $formatted_role = '';

    /* Initialize  DateTime object. */
    $myDateTime = new \DateTime();
    /* Set variable with new subscription date, today. */
    $subDate = $myDateTime->format('Y-m-d');
    /* Get current sub expiry date. */
    $field_subscription_expiry = $user->get('field_subscription_expiry')
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
          case 'Print & Digital Subscriber';
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

      $user->addRole($formatted_role);
      $user->set('field_subscription_date', $subDate);
      $user->set('field_subscription_expiry', $subExpiry);
    }

    try {
      if (!$user->save()) {
        throw new Exception("Unable to save user.");
      }
      $this->message .= "Hello $username, you are logged in, and your subscription is now valid through $this->subExpiry";
      \Drupal::messenger()->addMessage($this->message);
    }
    catch (Exception | EntityStorageException $e) {
      echo 'Caught Exception: ', $e->getMessage(), "\n";
      exit;
    }
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

}
