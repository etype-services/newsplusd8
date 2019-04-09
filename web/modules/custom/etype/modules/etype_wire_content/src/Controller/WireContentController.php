<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;

/**
 * Class ImportClassifiedController.
 *
 * @package Drupal\etype_wire_content\Controller
 */
class WireContentController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var WireContentController
   */
  protected $messenger;

  /**
   * ImportClassifiedController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_wire_content.settings');
    $this->messenger = Drupal::messenger();
  }

  /**
   * List Wire Content.
   *
   * @return array
   *   Nodes.
   */
  public function wireContent() {
    return ['#markup' => '<p>Hello.</p>'];
  }

}
