<?php

namespace Drupal\etype_commerce\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
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
  public function expireSubscribers() {
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
        $role = $loaded_entity->get('field_role')->getValue();
        $roles[] = $role[0]['value'];
      }
      catch (InvalidPluginDefinitionException $e) {
      }
      catch (PluginNotFoundException $e) {
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
        // $markup .= $userId . "<br />";
        // $markup .= $num . "<br />";
        // $markup .= $field_subscription_expiry[0]['value'] . "<br /><br />";
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
