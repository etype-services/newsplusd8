<?php

/**
 * @file
 * Drupal\etype_classifed_importer\Controller\ImportNewzWareController.
 */

namespace Drupal\etype_newzware_importer\Controller;

use Drupal;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\Node;
use Exception;

/**
 * Class ImportNewzWareController.
 *
 * @package Drupal\etype_newzware_importer\Controller
 */
class ImportNewzWareController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var ImportNewzWareController
   */
  protected $importUrl;

  /**
   * Var Setup.
   *
   * @var ImportNewzWareController
   */
  protected $messenger;

  /**
   * ImportNewzWareController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_newzware_importer.settings');
    $this->importUrl = $this->config->get('import_url');
    $this->messenger = Drupal::messenger();
  }

  /**
   * Import NewzWare Ads from nodes.
   *
   * @return array
   *   Nodes.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importNewzWareXml() {

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
    $query = Drupal::entityQuery('node');
    $query->condition('type', 'classified_ad');
    $nids = $query->execute();
    if (count($nids) > 0) {
      $storage_handler = Drupal::entityTypeManager()->getStorage('node');
      $entities = $storage_handler->loadMultiple($nids);
      $storage_handler->delete($entities);
    }
    // Log deletion.
    Drupal::logger('etype_newzware_importer')->notice("Deleted %num classified ads.", ['%num' => count($nids)]);

    $i = 0;
    foreach ($obj as $item) {

      // Ads do not have title, mostly.
      $str = substr($item->HTMLContent, 0, 25);
      $title = preg_replace("/[\n\r]/", " ", $str);

      if (!empty($title)) {
        // Get term id that matched VisionData category.
        $query = Drupal::entityQuery('taxonomy_term');
        $terms = $query->condition('field_newzware_class_number', $item->CLASS_NUMBER)
          ->execute();
        $ad_cat = reset($terms);

        $node = Node::create([
          'type' => 'classified_ad',
          'title' => $title,
          'body' => [
            'value' => $item->HTMLContent,
          ],
          'field_id' => $item->ADID,
          /* field machine name is for VisionData, no need to change it */
          'field_visiondata_category' => $item->CLASS_NUMBER,
          'status' => 1,
          'uid' => 1,
        ]);
        $node->save();
        $nid = $node->id();
        $alt = Node::load($nid);
        $alt->setCreatedTime(strtotime($item->STARTDATE));

        if (!empty($ad_cat)) {
          $alt->field_ad_category->target_id = (int) $ad_cat;
        }
        else {
          // Log/warn about missing category relationship.
          $message = sprintf("No category match for NewzWare category %s.\n%s",
            $item->CLASS_NUMBER, $item->HTMLContent);
          Drupal::logger('etype_newzware_importer')->notice($message);
          $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
        }

        $alt->save();

        $i++;

      }
      else {
        // Log/warn about missing category relationship.
        $message = sprintf("No title or description for ad, skipping.");
        Drupal::logger('etype_newzware_importer')->notice($message);
        $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
      }

    }
    // Log imported.
    Drupal::logger('etype_newzware_importer')->notice("Imported %num classified ads.", ['%num' => $i]);
    return ['#markup' => '<p>' . $i . ' classified ads were imported.</p>'];

  }

}

/**
 * Class ImportUrlMissingException.
 *
 * @package Drupal\etype_newzware_importer\Controller
 */
class ImportUrlMissingException extends Exception {

  /**
   * Constructs an ImportFileMissingException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import url defined. See eType NewzWare Importer settings.');
    parent::__construct($message);
  }

}

/**
 * Class XMLIsFalseException.
 *
 * @package Drupal\etype_newzware_importer\Controller
 */
class XMLIsFalseException extends Exception {

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
 * @package Drupal\etype_newzware_importer\Controller
 */
class AdObjectEmptyException extends Exception {

  /**
   * Constructs an AdObjectEmptyException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There do not seem to be any classified ads to import.');
    parent::__construct($message);
  }

}
