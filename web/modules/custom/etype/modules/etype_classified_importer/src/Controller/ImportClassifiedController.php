<?php

/**
 * @file
 * Contains
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController
 */

namespace Drupal\etype_classified_importer\Controller;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Class ImportFileMissingException.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class ImportFileMissingException extends \Exception {

  /**
   * ImportFileMissingException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import file(s) defined. See eType XML Importer settings.');
    parent::__construct($message);
  }

}

/**
 * Class ImportUrlMissingException.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class ImportUrlMissingException extends \Exception {

  /**
   * Constructs an ImportFileMissingException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import url defined. See eType XML Importer settings.');
    parent::__construct($message);
  }

}

/**
 * Class XMLIsFalseException.
 *
 * @package Drupal\etype_xml_importer\Controller
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
 * Class ImportOliveXMLController.
 *
 * @package Drupal\etype_xml_importer\Controller
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
   * ImportOliveXMLController constructor.
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

    /* throw Exception and return empty page with message if no file to import */
    try {
      if (empty($this->import_files)) {
        throw new ImportFileMissingException();
      }
    }
    catch (ImportFileMissingException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

  }

}
