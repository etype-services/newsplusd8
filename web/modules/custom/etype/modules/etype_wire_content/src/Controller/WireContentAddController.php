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
   * WireContentAddController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_wire_content.settings');
    $this->messenger = Drupal::messenger();
  }

  /**
   * Add Wire Content.
   *
   * TODO: make Node Type configurable.
   *
   * @param int $nid
   *   Node Identifier.
   *
   * @return null
   *   Return something.
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

    $new_entity = $storage->create([
      'type' => 'article',
      'title' => $node['title'],
      'body' => [
        'value' => $node['body'],
        'summary' => $node['summary'],
        'format' => 'full_html',
      ],
      'field_image' => $field_image,
      'uid' => 1,
      'status' => 0,
      'comment' => 0,
      'promote' => 0,
      'language' => $this->langCode,
    ]);
    $new_entity->save();


    /* Reset connection. */
    Database::setActiveConnection();
    return ['#markup' => "<p>$nid added.</p>"];
  }

}
