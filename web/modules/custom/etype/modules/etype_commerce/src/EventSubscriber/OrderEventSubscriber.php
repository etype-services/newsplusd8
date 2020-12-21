<?php

namespace Drupal\etype_commerce\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use League\Csv\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;
use Drupal\commerce_cart\Event\CartEntityAddEvent;
use Drupal\commerce_store\Entity\Store;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    $customer_id = $order->getCustomerId();
    $user = User::load($customer_id);
    $username = $user->getUsername();
    $message = '';
    $subExpiry = '';

    /* Extend subscription by 1 year from today if Sub Expiry is in past, or 1 year from Sub Expiry. */
    /* Initialize  DateTime object. */
    $myDateTime = new \DateTime();
    /* Set variable with new subscription date, today. */
    $subDate = $myDateTime->format('Y-m-d');
    /* Get current sub expiry date. */
    $field_subscription_expiry = $user->get('field_subscription_expiry')
      ->getValue();

    /* TODO: Prevent purchase of multiple subscriptions */

    /*
     * Loop over order items and process subscription.
     * There should only be one item
     */
    foreach ($order->getItems() as $key => $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $arr = $product_variation->get('attribute_subscription_type')->getValue();
      $target_id = ($arr[0]["target_id"]);
      $entity = \Drupal::entityTypeManager()
        ->getStorage('commerce_product_attribute')
        ->load('subscription_type')
        ->getValues();
      $arr2 = $entity[$target_id]->name->getValue();
      $role = $arr2[0]['value'];

      switch ($role) {
        case 'Print & Digital Subscriber';
          $formatted_role = 'print_digital_subscriber';
          break;

        default:
          $formatted_role = 'digital_subscriber';
      }

      $arr = $product_variation->get('attribute_duration')->getValue();
      $target_id = ($arr[0]["target_id"]);
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

      /* Is the Sub expiry date in the past, in the future, or does it exist? */
      if (isset($field_subscription_expiry[0]['value'])) {
        $oldSubExpiry = new \DateTime($field_subscription_expiry[0]['value']);
        $days = $myDateTime->diff($oldSubExpiry)->format('%R%a');
        if ($days > 0) {
          $subExpiry = $oldSubExpiry->add(new \DateInterval($formatted_duration))
            ->format('Y-m-d');
        } else {
          $subExpiry = $myDateTime->add(new \DateInterval($formatted_duration))
            ->format('Y-m-d');
        }
      } else {
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
      $message .= "Hello $username, you are logged in, and your subscription is now valid through $subExpiry";
      \Drupal::messenger()->addMessage($message);
    } catch (Exception $e) {
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
   */
  public function onCartEntityAdd(CartEntityAddEvent $event) {
    $orders = \Drupal::service('commerce_cart.cart_provider')->getCarts();
    $items = reset($orders)->getItems();
    $count = count($items);
    \Drupal::logger('etype_commerce')->notice($count);
    if ($count > 0) {
      $cart_manager = \Drupal::service('commerce_cart.cart_manager');
      foreach ($items as $item) {
        $i = 0;
        if ($i > 0) {
          $cart_manager->removeOrderItem($i, $item);
        }
        $i++;
      }
    }
  }

}
