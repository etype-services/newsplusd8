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
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function onPlaceTransition(WorkflowTransitionEvent $event) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();
    $customer_id = $order->getCustomerId();
    $user = User::load($customer_id);
    $myDateTime = new \DateTime();
    $subDate = $myDateTime->format('Y-m-d');
    $subExpiry = $myDateTime->add(new \DateInterval('P1Y'))->format('Y-m-d');
    foreach ($order->getItems() as $key => $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $role = $product_variation->get('field_role')->getValue();
      $user->addRole($role[0]['value']);
      $user->set('field_subscription_date', $subDate);
      $user->set('field_subscription_expiry', $subExpiry);
      $user->save();
    }
  }

}
