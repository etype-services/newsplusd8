<?php

namespace Drupal\etype\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ADAComplianceController adds ADA Compliance text.
 *
 * @package Drupal\etype\Controller
 */
class ADAComplianceController extends ControllerBase {

  /**
   * Class ADAComplianceController.
   *
   * @package Drupal\etype\Controller
   */
  public function adaCompliance(): array {
    return [
      '#title' => 'Americans with Disabilities Act Compliance Statement',
      '#theme' => 'ada_compliance',
    ];
  }

}
