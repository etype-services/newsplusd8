<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\etype_wire_content\Form\WireConnectionException;

/**
 * Class WireContentController.
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
   * WireContentController constructor.
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
    $result = $db->select('node', 'n')->fields('n', ['title'])
      ->execute()
      ->fetchAll();
    kint($result);


    /* Reset connection. */
    Database::setActiveConnection();
    return ['#markup' => '<p>Hello.</p>'];
  }

}
