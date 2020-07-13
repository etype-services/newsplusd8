<?php

namespace Drupal\etype_pico\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class EtypePicoEeditionController.
 *
 * @package Drupal\etype_pico\Controller
 */
class EtypePicoEeditionController extends ControllerBase {

  /**
   * Returns a render-able array.
   */
  public function content() {

    $user_name = Drupal::currentUser()->getAccountName();

    return [
      '#children' => "<p>iFrame code needed.</p>",
    ];
  }

}
