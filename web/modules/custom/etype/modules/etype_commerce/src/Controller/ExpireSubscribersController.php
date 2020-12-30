<?php

namespace Drupal\etype_commerce\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\user\Entity\User;

/**
 * Class ExpireSubscribersController updates role for expired subscribers.
 *
 * @package Drupal\etype_commerce\Controller
 */
class ExpireSubscribersController {

  /**
   * Change User role if subscription is expired.
   *
   * @throws \Exception
   */
  public function expireSubscribers(): array {
    $markup = '';
    $roles = [];
    /* Get the roles associated with subscription products. */
    $storage = \Drupal::service('entity_type.manager')->getStorage('commerce_product_variation')->getQuery();
    $result = $storage->execute();
    foreach ($result as $entityId) {
      try {
        $loaded_entity = \Drupal::entityTypeManager()
          ->getStorage('commerce_product_variation')
          ->load($entityId);
        $arr = $loaded_entity->get('attribute_subscription_type')->getValue();
        $target_id = ($arr[0]["target_id"]);
        $entity = \Drupal::entityTypeManager()
          ->getStorage('commerce_product_attribute')
          ->load('subscription_type')
          ->getValues();
        $arr2 = $entity[$target_id]->name->getValue();
        $role = $arr2[0]['value'];
        switch ($role) {
          case 'Print & Digital';
            $formatted_role = 'print_digital_subscriber';
            break;

          default:
            $formatted_role = 'digital_subscriber';
        }
        $roles[] = $formatted_role;
      }
      catch (InvalidPluginDefinitionException $e) {
      }
    }
    $query = \Drupal::service('entity_type.manager')->getStorage('user')->getQuery();
    $query->condition('roles', $roles, 'IN');
    $result = $query->execute();

    $myDateTime = new \DateTime();
    foreach ($result as $userId) {
      $user = User::load($userId);
      $field_subscription_expiry = $user->get('field_subscription_expiry')->getValue();
      // Default for no subscription expiry value.
      $num = -1;
      $username = $user->getUsername();
      if (isset($field_subscription_expiry[0]['value'])) {
        $subExpiry = new \DateTime($field_subscription_expiry[0]['value']);
        $days = $myDateTime->diff($subExpiry)->format('%R%a');
        $num = (int) $days;
      }
      if ($num < 0) {
        foreach ($roles as $role) {
          $user->removeRole($role);
        }
        $markup .= "<a href=\"/user/" . $userId . "/edit\" target=\"_blank\">" . $username . "'s</a> subscription expired on " . $field_subscription_expiry[0]['value'] . " and their access has been removed.";
        $user->save();
      }
    }
    return ['#markup' => $markup];
  }

}
