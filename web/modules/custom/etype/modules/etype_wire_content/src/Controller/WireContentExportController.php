<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;

/**
 * Class WireContentExportController.
 *
 * @package Drupal\etype_wire_content\Controller
 */
class WireContentExportController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var WireContentExportController
   */
  protected $messenger;

  /**
   * WireContentExportController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_wire_content.settings');
    $this->messenger = Drupal::messenger();
  }

  /**
   * Export Wire Content.
   *
   * @return array
   *   Nodes.
   */
  public function exportWireContent() {
    return ['#markup' => '<p>Hello.</p>'];
  }

}
