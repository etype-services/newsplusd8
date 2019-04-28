<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;

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
   *   Node Export Status in markup.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exportWireContent() {
    /* Find nodes to export. */
    $nids = Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->condition('field_section', [1, 2, 10], "IN")
      ->condition('status', '1')
      ->sort('changed', 'DESC')
      ->range(0, 20)
      ->execute();
    $storage_handler = Drupal::entityTypeManager()->getStorage('node');
    $nodes = $storage_handler->loadMultiple($nids);
    if (count($nodes) > 0) {
      /* Delete current matching rows in wire.node */
      $config = Drupal::config('system.site');
      $site_name = $config->get('name');
      Database::setActiveConnection('wire');
      $db = Database::getConnection();
      $db->delete('node')
        ->condition('site_name', $site_name)
        ->execute();
      foreach ($nodes as $node) {
        var_dump($node->get('title')->value);
      }
      /* Reset connection. */
      Database::setActiveConnection();
    }
    return ['#markup' => '<p>Hello.</p>'];
  }

}
