<?php

/**
 * @file
 * Drupal\etype_classifed_importer\Controller\ImportClassifiedController.
 */

namespace Drupal\etype_classified_importer\Controller;

use Drupal\Core\StringTranslation\TranslatableMarkup;

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
   * Constructs an ImportFileMissingException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There was a problem extracting XML from the file.');
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
   * Process XML file from VisionData.
   *
   * @return array
   *   Nodes.
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

    $ad_obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $i = 0;
    if (count($ad_obj) > 0) {
      var_dump($ad_obj);
    }

    return ['#markup' => ''];

  }

}
