<?php

namespace Drupal\etype\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ADAComplianceController.
 *
 * @package Drupal\etype\Controller
 */
class ADAComplianceController extends ControllerBase {

  /**
   * Class ADAComplianceController.
   *
   * @package Drupal\etype\Controller
   */
  public function adaCompliance() {
    return [
      '#title' => 'Americans with Disabilities Act Compliance Statement',
      '#theme' => 'ada_compliance',
    ];
  }

}