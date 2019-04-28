<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use \Exception;

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
   * Var Setup.
   *
   * @var WireContentExportController
   */
  protected $entityTypeManager;

  /**
   * Var Setup.
   *
   * @var WireContentExportController
   */
  protected $logger;

  /**
   * WireContentExportController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_wire_content.settings');
    $this->messenger = Drupal::messenger();
    $this->entityTypeManager = Drupal::entityTypeManager();
    $this->logger = Drupal::logger('etype_wire_content');
  }

  /**
   * Export Wire Content.
   *
   * @return array
   *   Node Export Status in markup.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * TODO: Set variables in Config form.
   */
  public function exportWireContent() {
    /* Find nodes to export. */
    $date_diff = strtotime("-20 days");
    $nids = Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->condition('field_section', [1, 2, 10], "IN")
      ->condition('status', '1')
      ->condition('changed', $date_diff, '>')
      ->sort('changed', 'DESC')
      ->execute();
    $storage_handler = $this->entityTypeManager->getStorage('node');
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
        try {
          $db->insert('node')
            ->fields([
              'nid' => $node->id(),
              'title' => $node->get('title')->value,
              'type' => $node->getType(),
              'language' => $node->get('langcode')->value,
              'site_name' => $site_name,
            ])
            ->execute();
        }
        catch (Exception $e) {
          // Log the exception.
          $this->logger->error($e->getMessage());
        }
      }
      /* Reset connection. */
      Database::setActiveConnection();
    }
    return ['#markup' => '<p>Hello.</p>'];
  }

}
