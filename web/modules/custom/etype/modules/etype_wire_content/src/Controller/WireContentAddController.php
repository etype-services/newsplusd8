<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\etype_wire_content\Form\WireConnectionException;

/**
 * Class WireContentAddController.
 *
 * @package Drupal\etype_wire_content\Controller
 */
class WireContentAddController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var WireContentAddController
   */
  protected $messenger;

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * WireContentAddController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_wire_content.settings');
    $this->messenger = Drupal::messenger();
    $this->entityTypeManager = Drupal::entityTypeManager();
  }

  /**
   * Add Wire Content.
   *
   * TODO: make Node Type configurable.
   * TODO: Make Images work.
   *
   * @param int $nid
   *   Node Identifier.
   *
   * @return array
   *   Markup.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function addWireContent(int $nid) {
    $check = _etype_wire_content_check_connection();
    /* throw Exception and return empty page with message if the wire database setings are missing */
    try {
      if ($check === 0) {
        throw new WireConnectionException();
      }
    }
    catch (WireConnectionException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    /* Connect to wire database and get settings. */
    Database::setActiveConnection('wire');
    $db = Database::getConnection();
    $result = $db->select('node', 'n')
      ->fields('n')
      ->condition('nid', $nid, '=')
      ->execute()
      ->fetchAll();
    $data = $result[0];

    $storage = $this->entityTypeManager->getStorage('node');

    /* Image */
    $field_image = [];
    if (!empty($data->file)) {
      $img = file_get_contents($data->file);
      $arr = explode("/", $data->file);
      $file = file_save_data($img, 'public://' . end($arr), FILE_EXISTS_RENAME);
      $field_image[] = [
        'target_id' => $file->id(),
        'alt' => $data->caption,
        'title' => $data->caption,
      ];
    }

    /* Use custom etype function in etype.module. */
    $summary = substrwords($data->body, 300);
    $new_entity = $storage->create([
      'type' => $data->type,
      'title' => $data->title,
      'body' => [
        'value' => $data->body,
        'summary' => $summary,
        'format' => 'full_html',
      ],
      'field_image' => $field_image,
      'uid' => 1,
      'status' => 0,
      'comment' => 0,
      'promote' => 0,
      'language' => $data->language,
    ]);
    $new_entity->save();

    /* Reset connection. */
    Database::setActiveConnection();
    return ['#markup' => '<p>The story <strong><a href="/node/' . $new_entity->id() . '">' . $data->title . '</a></strong> was added.</p>'];
  }

}
