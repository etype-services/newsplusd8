<?php

/**
 * @file
 * Contains
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController
 */

namespace Drupal\etype_xml_importer\Controller;

use Drupal;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\File\FileSystemInterface;
use Drupal\user\Entity\User;
use Exception;
use FilesystemIterator;
use ForceUTF8\Encoding;
use ZipArchive;
use SimpleXMLElement;

require_once __DIR__ . '/../Plugin/Encoding.php';


/**
 * Class ImportFileMissingException.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class ImportFileMissingException extends Exception {

  /**
   * ImportFileMissingException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('No import file(s) defined. See eType XML Importer settings.');
    parent::__construct($message);
  }

}

/**
 * Class XMLIsFalseException.
 *
 * @package Drupal\etype_xml_importer\Controller
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
 * Class UserErrorException.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class UserErrorException extends Exception {

  /**
   * Constructs an XMLIsFalseException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There was a problem creating the User.');
    parent::__construct($message);
  }

}

/**
 * Class ImportOliveXMLController.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class ImportOliveXMLController {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $importUrls;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $nodeType;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $subheadField;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $imageField;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $imageNumber;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $longCaptionField;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $author;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $langCode;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $extractDir;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $captionLimit = 512;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $longCaption;

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $i;

  /**
   * ImportOliveXMLController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype_xml_importer.settings');
    $this->importUrls = $this->config->get('importUrls');
    $this->nodeType = $this->config->get('nodeType');
    $this->langCode = 'en';
    $this->imageField = $this->config->get('imageField');
    $this->imageNumber = $this->config->get('imageNumber');
    $this->author = $this->config->get('author');
    $this->subheadField = $this->config->get('subheadField');
    $this->longCaptionField = $this->config->get('longCaptionField');
    $this->messenger = Drupal::messenger();
    $this->entityTypeManager = Drupal::entityTypeManager();
  }

  /**
   * Import Olive XML.
   *
   * @return array
   *   Markup
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importOliveXml() {

    /* throw Exception and return empty page with message if no file to import */
    try {
      if (empty($this->importUrls)) {
        throw new ImportFileMissingException();
      }
    }
    catch (ImportFileMissingException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    /* initialize markup */
    $markup = '';

    $import_file_array = explode("\n", $this->importUrls);

    /* loop over import files */
    foreach ($import_file_array as $item) {
      $markup .= '<p>Started import of ' . $item . '</p>';

      $rand = md5(time());
      $zip_file = "/tmp/" . $rand . ".zip";
      $this->extractDir = '/tmp/' . $rand . '/';

      /* Copy Zip file from url */
      $import_file = trim($item);
      if (!file_put_contents($zip_file, file_get_contents($import_file))) {
        $message = "eType XML Importer could not import " . $import_file . ".";
        $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
        continue;
      }

      /* Extract Zip Archive using PHP core */
      $zip = new ZipArchive();
      $res = $zip->open($zip_file);
      if ($res === TRUE) {
        $zip->extractTo($this->extractDir);
        $zip->close();
      }
      else {
        $message = "eType XML Importer could not open Zip Archive " . $zip_file . ".";
        $this->messenger->addMessage($message, $this->messenger::TYPE_WARNING);
        continue;
      }

      /* Loop over directory and get the Files */
      $fileSystemIterator = new FilesystemIterator($this->extractDir);
      $entries = [];
      foreach ($fileSystemIterator as $fileInfo) {
        $entry = $fileInfo->getFilename();
        if (strpos($entry, 'Section') !== FALSE) {
          $entries[] = $fileInfo->getFilename();
        }
      }
      /* Loop over found files and do the extraction */
      $t = 0;
      if (count($entries) > 0) {

        foreach ($entries as $entry) {
          $this->i = 0;
          $markup .= "<p>Extracting articles from $entry.<br />";

          $xml = file_get_contents((string) $this->extractDir . $entry);

          /* throw Exception and return empty page with message if xml is not extractable */
          try {
            if ($xml === FALSE) {
              throw new XMLIsFalseException();
            }
          }
          catch (XMLIsFalseException $e) {
            $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
            return ['#markup' => ''];
          }

          /* parse xml in each file */
          $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
          if (count($obj) > 0) {
            /* loop over items in Section file */
            foreach ($obj as $stub) {
              $item = $stub->item;
              /* xml object processing of stub which contains link, title, and description */
              foreach ($item as $k => $v) {
                $this->parseItem($v);
              }
            }
          }
          $markup .= "eType XML Importer found " . $this->i . " articles to import in $entry.</p>";
          $t += $this->i;
        } /* end foreach $entry */
      }
    }

    $message = 'eType XML Importer imported $t articles.';
    $markup .= "<p>$message</p>";
    Drupal::logger('my_module')->notice($message);
    return ['#markup' => $markup];
  }

  /**
   * Parse XML into importable format.
   *
   * @param \SimpleXMLElement $item
   *   XML data.
   *
   * @return array|string
   *   Markup
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function parseItem(SimpleXMLElement $item) {
    $array = (array) $item;

    // Title is not an object if the stub is valid.
    if (!is_object($array['title'])) {

      // Full article is in the linked file.
      $ar_file = $array['link'];
      $ar_xml = file_get_contents((string) $this->extractDir . $ar_file);

      /* parse article xhtml from link file */
      preg_match("/<prism:section>([^<]+)/", $ar_xml, $coincidencias);

      /* ignore if classifieds? */
      if ($this->config->get('importClassifieds') !== 1) {
        if ($coincidencias[1] == 'Classifieds') {
          return ['#markup' => ''];
        }
      }
      $array['section'] = $coincidencias[1];

      preg_match("/<dc:title>([^<]+)/", $ar_xml, $coincidencias);
      $array['title'] = substr($coincidencias[1], 0, 255);

      preg_match("/<prism:coverDate>([^<]+)/", $ar_xml, $coincidencias);
      $array['pub_date'] = $coincidencias[1];

      // Note: s flag makes dot match linebreaks as well.
      preg_match("'<body>(.*?)</body>'s", $ar_xml, $coincidencias);
      $body = $coincidencias[1];
      $body = preg_replace("'<xhtml:h1>(.*?)</xhtml:h1>'s", "", $body);
      $body = preg_replace("'<pam:media>(.*?)</pam:media>'s", "", $body);
      $body = preg_replace("'<xhtml:p prism:class=\"deck\">(.*?)</xhtml:p>'s", "", $body, 1);
      $body = preg_replace("'<xhtml:p prism:class=\"byline\">(.*?)</xhtml:p>'s", "", $body, 1);
      $body = preg_replace("/xhtml:([a-z]?)/", "$1", $body);
      // Fix tags.
      $array['body'] = trim($body);

      // Get the slugline.
      preg_match("'<xhtml:p prism:class=\"deck\">(.*?)</xhtml:p>'s", $ar_xml, $coincidencias);
      if (isset($coincidencias[1])) {
        $array['slugline'] = trim(strip_tags($coincidencias[1]));
      }
      else {
        $array['slugline'] = '';
      }

      // Get the byline.
      preg_match("'<xhtml:p prism:class=\"byline\">(.*?)</xhtml:b>'s", $ar_xml, $coincidencias);
      if (isset($coincidencias[1])) {
        $temp = preg_replace("/<xhtml:br \\/>/", " ", $coincidencias[1]);
        $temp = trim(strip_tags($temp));
        $temp = preg_replace("/^by\s+/i", "", $temp);
        $array['byline'] = ucwords(strtolower($temp));
      }
      else {
        $array['byline'] = '';
      }


      // Get the pull quote.
      preg_match("'<xhtml:p prism:class=\"pullQuote\">(.*?)</xhtml:b>'s", $ar_xml, $coincidencias);
      if (isset($coincidencias[1])) {
        $array['pulled_quote'] = trim(ucwords(strtolower(strip_tags($coincidencias[1]))));
      }
      else {
        $array['pulled_quote'] = '';
      }

      /* Images */
      $images = [];
      preg_match_all("'<pam:media>(.*?)</pam:media>'s", $ar_xml, $coincidencias);
      // Loop over matches and match data.
      if (!empty($coincidencias[1])) {
        $matches = $coincidencias[1];
        foreach ($matches as $item) {
          preg_match("/<dc:format>([^<]+)/", $item, $imatches);
          if (isset($imatches[1]) && $imatches[1] == 'image/jpg') {
            preg_match("'<pam:mediaReference pam:refid=\"(.*)\" />'", $item, $arr);
            if (isset($arr[1])) {
              $iarray = [];
              $iarray['image'] = $arr[1];
              preg_match("'<pam:caption>(.*?)</pam:caption>'s", $item, $arr);
              if (isset($arr[1])) {
                $iarray['caption'] = trim(strip_tags($arr[1]));
              }
              else {
                $iarray['caption'] = '';
              }
              $images[] = $iarray;
            }
          }
        }
      }

      $pub_date = strtotime($array['pub_date']);

      $node = [
        'title' => Encoding::toUTF8($array['title']),
        'summary' => strip_tags(Encoding::toUTF8(preg_replace("/\s+/", " ", $array['description']))),
        'body' => Encoding::toUTF8($array['body']),
      ];

      /* Create User based on byline */
      $node['uid'] = $this->author;
      $byline = Encoding::toUTF8($array['byline']);
      $byline = preg_replace('/\s+/i', " ", $byline);
      $byline = trim(ucfirst($byline));
      /* If the byline is longer than 60 characters it will not store and is most likely a mistake */
      if (strlen($byline) > 60) {
        $byline = "";
      }
      if (!empty($byline)) {
        $user = user_load_by_name($byline);
        if ($user === FALSE) {
          /* throw Exception and return empty page with message if no file to import */
          $rand = substr(md5(uniqid(mt_rand(), TRUE)), 0, 5);
          $user = User::create();
          $user->setPassword('goats random love ' . $rand);
          $user->enforceIsNew();
          $user->setUsername($byline);
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
          $node['uid'] = $user->id();
        }
        else {
          $node['uid'] = $user->id();
        }
      }

      if ($this->subheadField !== "None") {
        $node[$this->subheadField] = Encoding::toUTF8($array['slugline']);
      }

      $array = [];
      if (count($images) > 0) {
        $ptr = 0;
        foreach ($images as $image) {
          $ipath = (string) $this->extractDir . 'img/' . $image['image'];
          $array[] = [
            'name' => $image['image'],
            'path' => $ipath,
            'caption' => Encoding::toUTF8(preg_replace("/\s+/", " ", $image['caption'])),
          ];
          $ptr++;
          if (($this->imageNumber == 1) && ($ptr == 1)) {
            break;
          }
        }
        $node['images'] = $array;
      }

      // Otherwise field is initiated and shows empty on node page.
      if (!empty($array['pulled_quote'])) {
        $node['field_pulled_quote'] = $array['pulled_quote'];
      }

      $node['created'] = $pub_date;
      $this->createNode($node);
      $this->i++;
    }
  }

  /**
   * Create a Node.
   *
   * @param array $node
   *   Node array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createNode(array $node) {
    $storage = $this->entityTypeManager->getStorage('node');
    $field_image = [];
    if (isset($node['images'])) {
      $rand = substr(md5(uniqid(mt_rand(), TRUE)), 0, 10);
      $processedImages = $this->captions($node['images']);

      foreach ($processedImages as $image) {
        // Create file object from remote URL.
        $data = file_get_contents($image['path']);
        $file = file_save_data($data, 'public://' . $rand . '_' . $image['name'], FileSystemInterface::EXISTS_REPLACE);
        $field_image[] = [
          'target_id' => $file->id(),
          'alt' => $image['caption'],
          'title' => $image['caption'],
        ];
      }
    }
    $insert = [
      'type' => $this->nodeType,
      'title' => $node['title'],
      'body' => [
        'value' => $node['body'],
        'summary' => $node['summary'],
        'format' => 'full_html',
      ],
      'status' => 0,
      'comment' => 0,
      'promote' => 0,
      'language' => $this->langCode,
      $this->config->get('sectionField') => [['target_id' => $this->config->get('section')]],
    ];
    /* Add subhead to new entity. */
    if (isset($node[$this->subheadField])) {
      $insert[$this->subheadField] = $node[$this->subheadField];
    }
    /* Add images to new entity. */
    if (count($field_image) > 0) {
      $insert[$this->imageField] = $field_image;
    }
    /* Add long caption to new entity. */
    if (!empty($this->longCaption)) {
      $insert[$this->longCaptionField] = $this->longCaption;
    }
    if ($node['uid'] > 0) {
      $insert['uid'] = $node['uid'];
    }
    if ($this->config->get('importAsPremium') > 0) {
      $insert['premium_content'] = 1;
    }

    $new_entity = $storage->create($insert);
    $new_entity->save();

    /* Reset variable for next node. */
    $this->longCaption = '';
  }

  /**
   * Caption lengths.
   *
   * @param array $images
   *   Image array.
   *
   * @return array
   *   Processed Images.
   */
  protected function captions(array $images) {

    $processed = $images;

    foreach ($images as $k) {
      $length = strlen($k['caption']);
      /* If any caption is longer than the limit. */
      if ($length > $this->captionLimit) {
        $processed = $this->fixCaptions($images);
        break;
      }
    }
    return $processed;
  }

  /**
   * Fix Image Captions.
   *
   * @param array $images
   *   Node Images.
   *
   * @return array
   *   Processed Images.
   */
  protected function fixCaptions(array $images) {
    $processed = [];
    foreach ($images as $k) {
      $this->longCaption .= $k['caption'] . ' ';
      $k['caption'] = '';
      $processed[] = $k;
    }
    return $processed;
  }

}

