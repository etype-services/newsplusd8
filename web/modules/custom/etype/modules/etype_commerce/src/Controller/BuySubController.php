<?php

namespace Drupal\etype_commerce\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class BuySubController adds page to buy subscriptions.
 *
 * @package Drupal\etype\Controller
 */
class BuySubController extends ControllerBase {

  /**
   * Class BuySubController.
   *
   * @package Drupal\etype_commerce\Controller
   */
  public function buySub(): array {
    return [
      '#title' => 'Buy a Subscription',
      '#theme' => 'buysub',
    ];
  }

}
