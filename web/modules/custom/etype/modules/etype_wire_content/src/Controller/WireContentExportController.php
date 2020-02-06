<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Exception;

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
    $ptr = 0;
    $date_diff = strtotime("-20 days");
    $nids = Drupal::entityQuery('node')
      ->condition('type', $this->config->get('nodeType'))
      ->condition($this->config->get('field'), $this->config->get('sections'), "IN")
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
      $site = Drupal::request()->getHost();
      Database::setActiveConnection('wire');
      $db = Database::getConnection();
      $db->delete('node')
        ->condition('site_name', $site_name)
        ->execute();
      foreach ($nodes as $node) {
        try {
          $groups = implode(',', $this->config->get('groups'));
          $url = '';
          $caption = '';
          if ($node->get('field_image')->target_id > 0) {
            $caption = $node->get('field_image')->alt;
            $obj = File::load($node->get('field_image')->target_id);
            if (is_object($obj)) {
              $uri = $obj->getFileUri();
              $url = file_create_url($uri);
            }
          }
          $db->insert('node')
            ->fields([
              'nid' => $node->id(),
              'vid' => $node->get('vid')->value,
              'type' => $node->getType(),
              'language' => $node->get('langcode')->value,
              'title' => $node->get('title')->value,
              'body' => $node->get('body')->value,
              'file' => $url,
              'kicker' => '',
              'caption' => $caption,
              'uid' => 1,
              'status' => 0,
              'created' => $node->get('created')->value,
              'changed' => $node->get('changed')->value,
              'uuid' => $node->get('uuid')->value,
              'site' => $site,
              'site_name' => $site_name,
              'cluster' => $groups,
            ])
            ->execute();
          $ptr++;
        }
        catch (Exception $e) {
          // Log the exception.
          $this->logger->error($e->getMessage());
        }
      }
      /* Reset connection. */
      Database::setActiveConnection();
    }
    return ['#markup' => '<p>Exported ' . $ptr . ' nodes.</p>'];
  }

}
