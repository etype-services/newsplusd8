<?php

namespace Drupal\etype_xml_importer\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * Class EtypeXMLImporterConfigForm.
 *
 * @package Drupal\etype_xml_importer\Form
 */
class EtypeXMLImporterConfigForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * Config.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $conf;

  /**
   * Array of Node Types, used to choose which to import into.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields, array of field names associated with selected content type.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $fields = [];

  /**
   * Formats, deprecated.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $formats = [];

  /**
   * EtypeXMLImporterConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_xml_importer.settings');
    $this->entityFieldManager = Drupal::service('entity_field.manager');
    $this->getNodeTypeOptions();
    $this->getFields();
  }

  /**
   * Get node types and make options array.
   */
  protected function getNodeTypeOptions() {
    $nodeTypes = NodeType::loadMultiple();
    foreach ($nodeTypes as $nodeType) {
      $this->nodeTypeOptions[$nodeType->id()] = $nodeType->label();
    }
  }

  /**
   * Get the fields associated with selected node type.
   */
  protected function getFields() {
    /* fields is array of names of fields in nodeType */
    $fields = $this->conf->get('fields');
    /* If set, use setting. */
    if (is_array($fields) && count($fields) > 0) {
      $this->fields = $fields;
    }
    else {
      /* Check for nodeType. If it exists load a node */
      /* Use that to get nodeType FieldDefinitions */
      /* fields is array of FieldDefinitions keys */
      /* Used to build options array to select byline and subhead fields for import. */
      $type = $this->conf->get('nodeType');
      if (!empty($type)) {
        $nids = Drupal::entityQuery('node')
          ->condition('type', $type)
          ->range('0', '1')
          ->execute();
        $nid = reset($nids);
        /* Code is based on existence of articles, so a bug for new/empty sites. */
        if (isset($nid)) {
          $node = Node::load($nid);
          $fieldDefinitions = array_keys($node->getFieldDefinitions());
          $this->fields[] = "None";
          foreach ($fieldDefinitions as $key) {
            $this->fields[] = $key;
          }
        }
      }
    }
  }

  /**
   * Get the formats.
   */
  protected function getFormats() {
    $arr = filter_formats();
    $array = array_keys($arr);
    foreach ($array as $key) {
      $this->formats[] = $key;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_xml_importer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_xml_importer_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#markup'] = "Enable and edit import cron job at the <a href=\"/admin/config/system/cron/jobs/manage/etype_xml_importer_cron\">cron settings page</a>.";


    $form['importUrls'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Import Urls(s)'),
      '#description' => $this->t('Enter the full url or urls from which to import XML. PUt multiple files on separate lines.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('importUrls'),
      '#required' => TRUE,
    ];

    $form['nodeType'] = [
      '#title' => $this->t('Content Type'),
      '#type' => 'select',
      '#description' => $this->t('Choose a content type into which to import stories.'),
      '#options' => $this->nodeTypeOptions,
      '#default_value' => $this->conf->get('nodeType'),
    ];

    $form['subhead_field'] = [
      '#title' => $this->t('Subhead field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported subhead.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('subhead_field'),
    ];

    $form['byline_field'] = [
      '#title' => $this->t('Byline field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported byline.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('byline_field'),
    ];

    $form['uid'] = [
      '#title' => $this->t('User Id'),
      '#type' => 'textfield',
      '#description' => 'The User Id to set as Author of the imported content.',
      '#maxlength' => 4,
      '#size' => 4,
      '#default_value' => $this->conf->get('uid'),
    ];

    $form['import_classifieds'] = [
      '#title' => $this->t('Check to import Olive classified section.'),
      '#type' => 'checkbox',
      '#default_value' => $this->conf->get('import_classifieds'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    /* check for trailing slash on import_url */
    $val = $form_state->getValue('import_url');
    if (substr($val, -1) !== '/') {
      $val = $val . '/';
      $form_state->setValue('import_url', $val);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_xml_importer.settings')
      ->set('importUrls', $form_state->getValue('importUrls'))
      ->set('nodeType', $form_state->getValue('nodeType'))
      ->set('uid', $form_state->getValue('uid'))
      ->set('fields', $this->fields)
      ->set('subhead_field', $form_state->getValue('subhead_field'))
      ->set('byline_field', $form_state->getValue('byline_field'))
      ->set('import_classifieds', $form_state->getValue('import_classifieds'))
      ->save();
  }

}