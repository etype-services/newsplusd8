<?php

namespace Drupal\etype_commerce\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;

/**
 * Class OrderEventSubscriber adds Events on Order Completion.
 *
 * See https://docs.drupalcommerce.org/commerce2/developer-guide/adapting-from-1x/commerce-without-rules.
 *
 * @package Drupal\etype_commerce\EventSubscriber
 */
class OrderEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'commerce_order.place.post_transition' => ['onPlaceTransition'],
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
    /* Extend subscription by 1 year from today if Sub Expiry is in past, or 1 year from Sub Expiry. */
    /* Initialize  DateTime object. */
    $myDateTime = new \DateTime();
    /* Set variable with new subscription date, today. */
    $subDate = $myDateTime->format('Y-m-d');
    /* Get current sub expiry date. */
    $field_subscription_expiry = $user->get('field_subscription_expiry')->getValue();
    /* Is the Sub expiry date in the past, in the future, or does it exist? */
    if (isset($field_subscription_expiry[0]['value'])) {
      $oldSubExpiry = new \DateTime($field_subscription_expiry[0]['value']);
      $days = $myDateTime->diff($oldSubExpiry)->format('%R%a');
      if ($days > 0) {
        $subExpiry = $oldSubExpiry->add(new \DateInterval('P1Y'))->format('Y-m-d');
      }
      else {
        $subExpiry = $myDateTime->add(new \DateInterval('P1Y'))->format('Y-m-d');
      }
    }
    else {
      $subExpiry = $myDateTime->add(new \DateInterval('P1Y'))->format('Y-m-d');
    }
    /* TODO: Prevent purchase of multiple subscriptions */
    foreach ($order->getItems() as $key => $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $role = $product_variation->get('field_role')->getValue();
      $user->addRole($role[0]['value']);
      $user->set('field_subscription_date', $subDate);
      $user->set('field_subscription_expiry', $subExpiry);
    }
    $user->save();
    $message .= "Hello $username, you are logged in, and your subscription is now valid through $subExpiry";
    \Drupal::messenger()->addMessage($message);
  }

}
