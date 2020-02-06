<?php

namespace Drupal\etype_wire_content\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\Core\File\FileSystemInterface;
use Drupal\etype_wire_content\Form\WireConnectionException;
use Drupal\etype_xml_importer\Controller\UserErrorException;
use Drupal\user\Entity\User;

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
   *
   * @param int $nid
   *   Node Identifier.
   * @param string $site
   *   Site Identifier.
   *
   * @return array
   *   Markup.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function addWireContent(int $nid, string $site) {
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
      ->condition('site', $db->escapeLike($site), 'LIKE')
      ->execute()
      ->fetchAll();
    $data = $result[0];

    $storage = $this->entityTypeManager->getStorage('node');

    /* Image */
    $field_image = [];
    if (!empty($data->file)) {
      $img = file_get_contents($data->file);
      var_dump($img);
      exit;
      $arr = explode("/", $data->file);
      $file = file_save_data($img, 'public://' . end($arr), FileSystemInterface::EXISTS_REPLACE);
      $alt = empty($data->caption) ? "Image" : $data->caption;
      $field_image[] = [
        'target_id' => $file->id(),
        'alt' => $alt,
        'title' => $data->caption,
      ];
    }

    $uid = $this->config->get('author');
    $body = trim($data->body);
    $target = trim(strip_tags($body));
    preg_match("/^by\s+([-A-z‘]+\s+[-A-z‘]+)/i", $target, $matches);
    if (!empty($matches[1])) {
      $pattern = "/" . $matches[0] . "/";
      $body = preg_replace($pattern, "", $body);
      $target = preg_replace($pattern, "", $target);
      $user = user_load_by_name($matches[1]);
      if ($user === FALSE) {
        /* throw Exception and return empty page with message if no file to import */
        $rand = substr(md5(uniqid(mt_rand(), TRUE)), 0, 5);
        $user = User::create();
        $user->setPassword('goats random love ' . $rand);
        $user->enforceIsNew();
        $user->setUsername($matches[1]);
        $user->activate();
        try {
          if (!$user->save()) {
            throw new UserErrorException();
          }
        }
        catch (UserErrorException $e) {
          $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
          return ['#markup' => ''];
        }
        $uid = $user->id();
      }
    }

    /* Use custom etype function in etype.module. */
    $summary = substrwords(trim($target), 300);
    $new_entity = $storage->create([
      'type' => $data->type,
      'title' => $data->title,
      'body' => [
        'value' => trim($body),
        'summary' => $summary,
        'format' => 'full_html',
      ],
      'field_subhead' => $summary,
      'field_image' => $field_image,
      'uid' => $uid,
      'status' => 0,
      'comment' => 0,
      'promote' => 0,
      'language' => $data->language,
      'field_section' => [['target_id' => $this->config->get('section')]],
    ]);
    $new_entity->save();

    /* Reset connection. */
    Database::setActiveConnection();
    return ['#markup' => '<p>The story <strong><a href="/node/' . $new_entity->id() . '/edit">' . $data->title . '</a></strong> was added.</p>'];
  }

}

