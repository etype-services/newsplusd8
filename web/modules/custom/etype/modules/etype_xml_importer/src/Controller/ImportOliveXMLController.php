<?php

/**
 * @file
 * Contains.
 *
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController.
 */

namespace Drupal\etype_xml_importer\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\File\FileSystemInterface;
use Drupal\etype_xml_importer\Plugin\Encoding;
use Drupal\user\Entity\User;
use Drupal\Core\Database\Database;

/**
 * Class ImportOliveXMLController imports XML content.
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
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $i;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $entry;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $issue;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $publication;

  /**
   * Var Setup.
   *
   * @var ImportOliveXMLController
   */
  protected $storage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * ImportOliveXMLController constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('etype_xml_importer.settings');
    $this->importUrls = $this->config->get('importUrls');
    $this->nodeType = $this->config->get('nodeType');
    $this->langCode = 'en';
    $this->imageField = $this->config->get('imageField');
    $this->imageNumber = $this->config->get('imageNumber');
    $this->author = $this->config->get('author');
    $this->subheadField = $this->config->get('subheadField');
    $this->longCaptionField = $this->config->get('longCaptionField');
    $this->messenger = \Drupal::messenger();
    try {
      $this->storage = \Drupal::entityTypeManager()->getStorage('node');
    }
    catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
    }
    $this->moduleHandler = \Drupal::service('module_handler');
    $this->database = Database::getConnection();
  }

  /**
   * Return string.
   *
   * @return string
   *   String
   */
  public function __toString(): string {
    return (string) $this->entry;
  }

  /**
   * Import Olive XML.
   *
   * @return array
   *   Markup
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importOliveXml(): array {

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

    /* initialize variables */
    $markup = '';
    $t = 0;

    $import_file_array = explode("\n", trim($this->importUrls));

    /* Delete Unpublished Nodes first */
    if ($this->config->get('deleteUnpublished') == 1) {
      $nids = \Drupal::entityQuery('node')
        ->condition('type', $this->nodeType)
        ->condition('status', 0, "=")
        ->execute();

      try {
        $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
        $entities = $storage_handler->loadMultiple($nids);
        $storage_handler->delete($entities);
      } catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
      }

      $num = count($nids);
      $message = "eType XML Importer deleted $num unpublished articles.";
      $markup .= "<p>$message</p>";
      \Drupal::logger('etype_xml_importer')->notice($message);
    }

    /* loop over import files from config */
    foreach ($import_file_array as $item) {
      $markup .= '<p><strong>STARTED IMPORT OF ' . $item . '</strong></p>';

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
      $zip = new \ZipArchive();
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
      $fileSystemIterator = new \FilesystemIterator($this->extractDir);
      $entries = [];
      foreach ($fileSystemIterator as $fileInfo) {
        $section = $fileInfo->getFilename();
        if (strpos($section, 'Section') !== FALSE) {
          $entries[] = $section;
        }
      }
      /* Loop over found Section files and do the extraction */
      $t = 0;
      if (count($entries) > 0) {
        foreach ($entries as $this->entry) {
          $this->i = 0;
          $markup .= "<p>Extracting articles from $this->entry.<br />";

          $xml = file_get_contents((string) $this->extractDir . $this->entry);

          /* throw Exception and return empty page with message if xml is not extractable */
          try {
            if ($xml === FALSE) {
              throw new XMLIsFalseException();
            }
          }
          catch (XMLIsFalseException $e) {
            $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
            $markup .= "<p>XMLIsFalseException thrown for $this->entry.</p>";
          }

          /* parse xml in each file */
          $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

          /* Get Issue and Publication */
          $str = $obj->channel->title;
          $arr = explode(' - ', $str);
          $date = \DateTime::createFromFormat("l, j F, Y", $arr[1]);
          $this->issue = $date->format("U");
          $this->publication = $arr[0];

          if (is_object($obj) && (count($obj) > 0)) {
            /* loop over items in Section file */
            foreach ($obj as $stub) {
              $item = $stub->item;
              /* xml object processing of stub which contains link, title, and description */
              foreach ($item as $k => $v) {
                $message = $this->parseItem($v);
                $markup .= $message;
              }
            }
          }
          else {
            $markup .= "$obj is not an object in $this->entry.</p>";
          }
          $markup .= "eType XML Importer found " . $this->i . " articles to import in $this->entry.</p>";
          $t += $this->i;
        } /* end foreach $entry */
      }
    }

    $message = "eType XML Importer imported $t articles.";
    $markup .= "<p>$message</p>";
    \Drupal::logger('etype_xml_importer')->notice($message);
    return ['#markup' => $markup];
  }

  /**
   * Parse XML into importable format.
   *
   * @param \SimpleXMLElement $item
   *   XML data, contains link, title, description.
   *
   * @return array|string|null
   *   Markup
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function parseItem(\SimpleXMLElement $item) {
    $item_array = (array) $item;
    $message = '';

    // Title is not an object if the stub is valid.
    if (is_object($item_array['title'])) {
      return '<p>' . $item_array['link'] . ' has an issue, title seems to be empty. Please check ' . $this->entry . ' and ' . $item_array['link'] . '.</p>';
    }

    // If the description is empty in the Section file probably an error.
    $desc = strip_tags($item_array['description']);
    if (empty($desc)) {
      return '<p>' . $item_array['link'] . ' has an issue, description is empty. Please check ' . $this->entry . ' and ' . $item_array['link'] . '.</p>';
    }
    else {
      $array['description'] = $desc;
    }

    // Full article is in the linked file.
    $ar_file = $item_array['link'];
    $ar_xml = file_get_contents((string) $this->extractDir . $ar_file);

    /* Parse article xhtml from link file */

    /* Get section */
    preg_match("/<prism:section>([^<]+)/", $ar_xml, $coincidencias);

    /* Ignore if classifieds? */
    if ($this->config->get('importClassifieds') !== 1) {
      if ($coincidencias[1] == 'Classifieds') {
        return ['#markup' => ''];
      }
    }
    /* Get section */
    $section_name = $coincidencias[1];
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $section_name, 'vid' => 'sections']);
    $term = reset($term);
    if ($term != FALSE) {
      $term_id = $term->id();
    }
    else {
      $term_id = $this->config->get('section');
    }

    /* Get title */
    preg_match("/<dc:title>([^<]+)/", $ar_xml, $coincidencias);
    $array['title'] = trim(substr($coincidencias[1], 0, 255));

    /* Get identifier */
    preg_match("/<dc:identifier>([^<]+)/", $ar_xml, $coincidencias);
    $array['identifier'] = trim(substr($coincidencias[1], 0, 255));

    /* Check for Duplicates */
    if (entityTypeHasField('field_issue_identifier', 'node')) {
      if (!empty($array['identifier'])) {
        $nids = \Drupal::entityQuery('node')
          ->condition('type', $this->nodeType)
          ->condition('title', $array['title'], "=")
          ->condition('field_issue_identifier', $array['identifier'], "=")
          ->execute();

        if (count($nids) > 0) {
          return "Duplicate found for <strong>" . $array['title'] . " / " . $array['identifier'] . "</strong>. Story was not imported. <br />";
        }
      }
      else {
        $message .= "No identifier for  <strong>" . $array['title'] . "</strong>.<br />";
      }
    }

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
          preg_match("'<pam:mediaReference pam:refid=\"(.*)\"\s*/>'", $item,
            $arr);
          if (isset($arr[1])) {
            $iArray = [];
            $iArray['image'] = $arr[1];
            preg_match("'<pam:caption>(.*?)</pam:caption>'s", $item, $arr);
            if (isset($arr[1])) {
              $iArray['caption'] = trim(strip_tags($arr[1]));
            }
            else {
              $iArray['caption'] = '';
            }
            $images[] = $iArray;
          }
        }
      }
    }

    $pub_date = strtotime($array['pub_date']);

    /* Make sure summary is long enough */
    $summary = strip_tags((new Encoding)->toUtf8(preg_replace("/\s+/", " ", $array['description'])));
    if (strlen($summary) < 200) {
      $string = strip_tags((new Encoding)->toUtf8(preg_replace("/\s+/", " ", $array['body'])));
      $arr = explode('. ', $string);
      $summary = implode('. ', array_slice($arr, 0, 2));
      $summary .= '.';
    }

    $node = [
      'title' => (new Encoding)->toUtf8($array['title']),
      'summary' => $summary,
      'body' => (new Encoding)->toUtf8($array['body']),
      'identifier' => $array['identifier'],
    ];

    /* Create User based on byline */
    $node['uid'] = $this->author;
    $byline = (new Encoding)->toUtf8($array['byline']);
    $byline = preg_replace('/\s+/', " ", $byline);
    $byline = preg_replace('/^By:/i', " ", $byline);
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
        $user->addRole('reporter');
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
      $node[$this->subheadField] = (new Encoding)->toUtf8($array['slugline']);
    }

    $images_array = [];
    if (count($images) > 0) {
      $ptr = 0;
      foreach ($images as $image) {
        $ipath = (string) $this->extractDir . 'img/' . $image['image'];
        $images_array[] = [
          'name' => $image['image'],
          'path' => $ipath,
          'caption' => (new Encoding)->toUtf8(preg_replace("/\s+/", " ", $image['caption'])),
        ];
        $ptr++;
        if (($this->imageNumber == 1) && ($ptr == 1)) {
          break;
        }
      }
      $node['images'] = $images_array;
    }

    // Otherwise field is initiated and shows empty on node page.
    if (!empty($array['pulled_quote'])) {
      $pulled_quote = $array['pulled_quote'];
      $node['field_pulled_quote'] = $pulled_quote;
    }

    $node['created'] = $pub_date;
    $node['term_id'] = $term_id;
    /* Create the Node */
    $this->createNode($node);
    $this->i++;
    $message .= "Imported <strong>" . $node['title'] . " / " . $array['identifier'] . "</strong>.<br />";
    return $message;
  }

  /**
   * Create a Node.
   *
   * @param array $node
   *   Node array.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Exception
   */
  protected function createNode(array $node) {
    $field_image = [];
    if (isset($node['images'])) {
      $rand = substr(md5(uniqid(mt_rand(), TRUE)), 0, 10);
      $processedImages = $this->captions($node['images']);

      foreach ($processedImages as $image) {
        // Create file object from remote URL.
        $data = file_get_contents($image['path']);
        $file = file_save_data($data, 'public://' . $rand . '_' . $image['name'], FileSystemInterface::EXISTS_REPLACE);
        $caption = empty($image['caption']) ? "Alt Text for Image" : $image['caption'];
        $field_image[] = [
          'target_id' => $file->id(),
          'alt' => $caption,
          'title' => $caption,
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
      $this->config->get('sectionField') => [['target_id' => $node['term_id']]],
    ];
    /* Add subhead to new entity. */
    if (isset($node[$this->subheadField])) {
      $insert[$this->subheadField] = $node[$this->subheadField];
    }

    /* Add issue to new entity. */
    if (entityTypeHasField('field_issue', 'node')) {
      $insert['field_issue'] = $this->issue;
    }

    /* Add publication to new entity. */
    if (entityTypeHasField('field_publication', 'node')) {
      $insert['field_publication'] = $this->publication;
    }

    /* Add issue identifier to new entity. */
    if (entityTypeHasField('field_issue_identifier', 'node')) {
      $insert['field_issue_identifier'] = $node['identifier'];
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

    $new_entity = $this->storage->create($insert);
    $new_entity->save();
    $nid = $new_entity->id();

    /* Reset variable for next node. */
    $this->longCaption = '';

    /* Add Image Captions */
    if ($this->moduleHandler->moduleExists('image_field_caption')) {
      if (isset($node['images'])) {
        $vid = $this->storage->getLatestRevisionId($nid);
        $i = 0;
        foreach ($node['images'] as $image) {
          $caption = empty($image['caption']) ? "Alt Text for Image" : $image['caption'];
          $query = $this->database->insert('image_field_caption');
          $query
            ->fields([
              'entity_type' => 'node',
              'bundle' => $this->nodeType,
              'field_name' => $this->imageField,
              'entity_id' => $nid,
              'revision_id' => $vid,
              'language' => 'en',
              'delta' => $i,
              'caption' => $caption,
              'caption_format' => 'plain_text',
            ])
            ->execute();
          $i++;
        }
      }
    }
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
  protected function captions(array $images): array {

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
  protected function fixCaptions(array $images): array {
    $processed = [];
    foreach ($images as $k) {
      $this->longCaption .= $k['caption'] . ' ';
      $k['caption'] = '';
      $processed[] = $k;
    }
    return $processed;
  }

}


/**
 * Create Exception for missing Import File.
 *
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
 * Class XMLIsFalseException reports a problem extracting XML.
 *
 * @package Drupal\etype_xml_importer\Controller
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
 * Class UserErrorException reports a problem creating new User.
 *
 * @package Drupal\etype_xml_importer\Controller
 */
class UserErrorException extends \Exception {

  /**
   * Constructs an XMLIsFalseException.
   */
  public function __construct() {
    $message = new TranslatableMarkup('There was a problem creating the User.');
    parent::__construct($message);
  }

}

