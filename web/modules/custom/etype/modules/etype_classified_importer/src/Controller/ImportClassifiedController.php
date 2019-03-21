<?php

/**
 * @file
 * Drupal\etype_classifed_importer\Controller\ImportClassifiedController.
 */

namespace Drupal\etype_classified_importer\Controller;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\Node;

/**
 * Class ImportUrlMissingException.
 *
 * @package Drupal\etype_classified_importer\Controller
 */
class ImportUrlMissingException extends \Exception {

  /**
   * Constructs an ImportFileMissingException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import url defined. See eType Classified Importer settings.');
    parent::__construct($message);
  }

}

/**
 * Class XMLIsFalseException.
 *
 * @package Drupal\etype_classified_importer\Controller
 */
class XMLIsFalseException extends \Exception {

  /**
   * Constructs an XMLIsFalseException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There was a problem extracting XML from the file.');
    parent::__construct($message);
  }

}

/**
 * Class AdObjectEmptyException.
 *
 * @package Drupal\etype_classified_importer\Controller
 */
class AdObjectEmptyException extends \Exception {

  /**
   * Constructs an AdObjectEmptyException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There do not seem to be any classified ads to import.');
    parent::__construct($message);
  }

}

/**
 * Class ImportClassifiedController.
 *
 * @package Drupal\etype_classified_importer\Controller
 */
class ImportClassifiedController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $importUrl;

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $messenger;

  /**
   * ImportClassifiedController constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('etype_classified_importer.settings');
    $this->importUrl = $this->config->get('import_url');
    $this->messenger = \Drupal::messenger();
  }

  /**
   * Import Classified Ads from nodes.
   *
   * @return array
   *   Nodes.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importClassifiedXml() {

    /* throw Exception and return empty page with message if no url to import from */
    try {
      if (empty($this->importUrl)) {
        throw new ImportUrlMissingException();
      }
    }
    catch (ImportUrlMissingException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    $xml = file_get_contents($this->importUrl);
    try {
      if ($xml == FALSE) {
        throw new XMLIsFalseException();
      }
    }
    catch (XMLIsFalseException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    try {
      if (count($obj) == 0) {
        throw new AdObjectEmptyException();
      }
    }
    catch (AdObjectEmptyException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    // Delete old ads.
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'classified_ad');
    $tids = $query->execute();
    if (count($tids) > 0) {
      $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
      $entities = $storage_handler->loadMultiple($tids);
      $storage_handler->delete($entities);
    }
    // Log deletion.
    \Drupal::logger('etype_classified_importer')->notice("Deleted %num classified ads.", ['%num' => count($tids)]);

    foreach ($obj as $item) {
      $title = empty($item->ItemTitle) ? substr($item->ItemDesc, 0, 25) : $item->ItemTitle;
      $data = [
        'title' => $title,
        'body' => $item->ItemDesc,
        'id' => $item->ItemId,
        'category' => $item->categoryId,
        'date' => $item->StartDate,
      ];
      $this->createNode($data);
    }

    return ['#markup' => ''];

  }

  /**
   * Create the node.
   *
   * @param array $data
   *   Data.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createNode(array $data) {
    $entity = Node::create([
      'type' => 'classified_ad',
      'title' => $data['title'],
      'body' => [
        'value' => $data['body'],
      ],
      'field_id' => $data['id'],
      'field_category' => $data['category'],
      'status' => 1,
      'uid' => 1,
      'created'  => $data['date'],
    ]);
    $entity->save();
  }

}
