<?php

namespace Drupal\etype_pico\Controller;

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

    return [
      '#children' => "<p>iFrame code needed.</p>",
    ];
  }

}
