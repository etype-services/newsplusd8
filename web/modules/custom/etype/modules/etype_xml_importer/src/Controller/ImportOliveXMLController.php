<?php

/**
 * @file
 * Contains
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController
 */

namespace Drupal\etype_xml_importer\Controller;
use Drupal\Core\StringTranslation\TranslatableMarkup;

require_once(__DIR__  . '/../Plugin/Encoding.php');
use \ForceUTF8\Encoding;

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
  protected $node_type;

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
  protected $lang_code;

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
    $this->node_type = $this->config->get('node_type');
    $this->lang_code = 'en';
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

      /* Loop over directory and get the Files */
      $fileSystemIterator = new \FilesystemIterator($extract_dir);
      $entries = array();
      foreach ($fileSystemIterator as $fileInfo) {
        $entry = $fileInfo->getFilename();
        if(strpos($entry, 'Section') !== FALSE) {
          $entries[] = $fileInfo->getFilename();
        }
      }
      /* Loop over found files and do the extraction */
      $i = 0;
      if (count($entries) > 0) {

        $values = array(
          'type' => $this->node_type,
          'uid' => 1,
          'status' => 0,
          'comment' => 0,
          'promote' => 0,
          'language' => $this->lang_code,
        );

        foreach ($entries as $entry) {

          $xml = file_get_contents($extract_dir . $entry);
          if ($xml !== FALSE) {
            /* parse xml in each file */
            $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (sizeof($obj) > 0) {
              /* loop over items in Section file */
              foreach ($obj as $stub) {
                $item = $stub->item;
                /* xml object processing of stub which contains link, title, and description */
                foreach ($item as $k => $v) {
                  $array = (array) $v;

                  // title is not an object if the stub is valid
                  if (!is_object($array['title'])) {

                    // full article is in the linked file
                    $ar_file =  $array['link'];
                    $ar_xml = file_get_contents($extract_dir . $ar_file);

                    /* parse article xhtml from link file */
                    preg_match("/<prism:section>([^<]+)/", $ar_xml, $coincidencias);

                    /* ignore classifieds? */
                    $etype_xml_import_classifieds = $this->config->get('import_classifieds');
                    if ($etype_xml_import_classifieds !== 1) {
                      if ($coincidencias[1] == 'Classifieds') {
                        continue;
                      }
                    }
                    $array['section'] = $coincidencias[1];

                    preg_match("/<dc:title>([^<]+)/", $ar_xml, $coincidencias);
                    // echo 'title should be: ' . $coincidencias[1] . "\n";
                    $array['title'] = substr($coincidencias[1], 0, 255);

                    preg_match("/<prism:coverDate>([^<]+)/", $ar_xml, $coincidencias);
                    $array['pub_date'] = $coincidencias[1];

                    // s flag makes dot match linebreaks as well
                    preg_match("'<body>(.*?)</body>'s", $ar_xml, $coincidencias);
                    $body = $coincidencias[1];
                    $body = preg_replace("'<xhtml:h1>(.*?)</xhtml:h1>'s","", $body);
                    $body = preg_replace("'<pam:media>(.*?)</pam:media>'s","", $body);
                    $body = preg_replace("'<xhtml:p prism:class=\"deck\">(.*?)</xhtml:p>'s", "", $body, 1);
                    $body = preg_replace("'<xhtml:p prism:class=\"byline\">(.*?)</xhtml:p>'s", "", $body, 1);
                    $body = preg_replace("/xhtml:([a-z]?)/", "$1", $body);
                    // fix tags
                    $array['body'] = trim($body);

                    // get the slugline
                    preg_match("'<xhtml:p prism:class=\"deck\">(.*?)</xhtml:p>'s", $ar_xml, $coincidencias);
                    if (isset($coincidencias[1])) {
                      $array['slugline'] = trim(strip_tags($coincidencias[1]));
                    } else {
                      $array['slugline'] = '';
                    }

                    // get the byline
                    preg_match("'<xhtml:p prism:class=\"byline\">(.*?)</xhtml:b>'s", $ar_xml, $coincidencias);
                    if (isset($coincidencias[1])) {
                      $temp = preg_replace("/<xhtml:br \\/>/", " ", $coincidencias[1]);
                      $temp = trim(strip_tags($temp));
                      $temp = preg_replace("/^by\s*/i", "", $temp);
                      $array['byline'] = ucwords(strtolower($temp));
                    } else {
                      $array['byline'] = '';
                    }

                    // get the pull quote
                    preg_match("'<xhtml:p prism:class=\"pullQuote\">(.*?)</xhtml:b>'s", $ar_xml, $coincidencias);
                    if (isset($coincidencias[1])) {
                      $array['pulled_quote'] = trim(ucwords(strtolower(strip_tags($coincidencias[1]))));
                    } else {
                      $array['pulled_quote'] = '';
                    }

                    // echo $ar_xml;

                    /* Images */
                    $images = array();
                    preg_match_all("'<pam:media>(.*?)</pam:media>'s", $ar_xml, $coincidencias);
                    // loop over matches and match data
                    if (! empty($coincidencias[1])) {
                      $matches = $coincidencias[1];
                      foreach ($matches as $item) {
                        preg_match("/<dc:format>([^<]+)/", $item, $imatches);
                        if (isset($imatches[1]) && $imatches[1] == 'image/jpg') {
                          preg_match("'<pam:mediaReference pam:refid=\"(.*)\" />'", $item, $arr);
                          if (isset($arr[1])) {
                            $iarray = array();
                            $iarray['image'] = $arr[1];
                            preg_match("'<pam:caption>(.*?)</pam:caption>'s", $item, $arr);
                            if (isset($arr[1])) {
                              $iarray['caption'] = trim(strip_tags($arr[1]));
                            } else {
                              $iarray['caption'] = '';
                            }
                            $images[] = $iarray;
                          }
                        }
                      }
                    }

                    $pub_date = strtotime($array['pub_date']);

                    $node = array(
                      'title' => Encoding::toUTF8($array['title']),
                      'summary' => strip_tags(Encoding::toUTF8($array['description'])),
                      'body' => Encoding::toUTF8($array['body']),
                      $this->byline_field => substr(Encoding::toUTF8($array['byline']), 0, 255), // trim to field length
                      $this->subhead_field => Encoding::toUTF8($array['slugline']),
                      'field_date1' => $pub_date, // Laurel Outlook
                    );

                    $array = [];
                    if (count($images) > 0) {
                      foreach ($images as $image) {
                        $ipath = $extract_dir . 'img/' . $image['image'];
                        $array[] = [
                          'name' => $image['image'],
                          'path' => $ipath,
                          'caption' => Encoding::toUTF8($image['caption']),
                        ];
                      }
                      $node['images'] = $array;
                    }

                    // otherwise field is initiated and shows empty on node page
                    if (! empty($array['pulled_quote'])) {
                      $node['field_pulled_quote'] = $array['pulled_quote'];
                    }

                    $var = print_r($node, true);
                    $markup .= "$var\n\n\n";

                    $values['created'] = $pub_date;
                    // etype_xml_importer_entity_create($node, $values);
                    // exit;
                    $i++;
                    // echo $i . ' ';
                  }
                }
              }
            }
          }
        }
      }
    }

    return ['#markup' => $markup];
  }

}

