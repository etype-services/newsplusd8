<?php

/**
 * @file
 * Contains
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController
 */

namespace Drupal\etype_xml_importer\Controller;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Archiver\Zip;

class ImportFileMissingException extends \Exception {

  /**
   * Constructs an ImportFileMissingException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import file(s) defined. See eType XML Importer settings.');
    parent::__construct($message);
  }

}

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
 * @inheritdoc
 */
class ImportOliveXMLController {

  /**
   * @var
   */
  protected $config;

  /**
   * @var
   */
  protected $import_files;

  /**
   * @var
   */
  protected $byline_field;

  /**
   * @var
   */
  protected $subhead_field;

  /**
   * @var
   */
  protected $import_url;

  /**
   * @var
   */
  protected $messenger;

  /**
   * ImportOliveXMLController constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('etype_xml_importer.settings');
    $this->import_url = $this->config->get('import_url');
    $this->import_files = $this->config->get('import_files');
    $this->byline_field = $this->config->get('byline_field');
    $this->subhead_field = $this->config->get('subhead_field');
    $this->messenger = \Drupal::messenger();
  }

  /**
   * ImportOliveXMLController
   * @return mixed
   */
  public function ImportOliveXml() {

    /* throw Exception and return empty page with message if no url to import from */
    try {
      if (empty($this->import_url)) {
        throw new ImportUrlMissingException();
      }
    } catch(ImportUrlMissingException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    /* throw Exception and return empty page with message if no file to import */
    try {
      if (empty($this->import_files)) {
        throw new ImportFileMissingException();
      }
    } catch(ImportFileMissingException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    /* initialize markup */
    $markup = '';

    $import_file_array = explode(',', $this->import_files);
    foreach ($import_file_array as $item) {
      $markup .= 'Started import of ' . $item . "\n";
      $rand = md5(time());
      $zip_file = "/tmp/" . $rand . ".zip";
      $extract_dir = '/tmp/' . $rand . '/';

      /* Copy Zip file from url */
      $import_file = $this->import_url . trim($item);
      if (!file_put_contents($zip_file, file_get_contents($import_file))) {
        $message = "eType XML Importer could not import " . $import_file . ".";
        $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
        continue;
      }

      /* Extract Zip Archive using PHP core */
      $zip = new \ZipArchive;
      $res = $zip->open($zip_file);
      if ($res === TRUE) {
        $zip->extractTo($extract_dir);
        $zip->close();
      } else {
        $message = "eType XML Importer could not open Zip Archive " . $zip_file . ".";
        $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
        continue;
      }
    }

    return ['#markup' => $markup];
  }

}

