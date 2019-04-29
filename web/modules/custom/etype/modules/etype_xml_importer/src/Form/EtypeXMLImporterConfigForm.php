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
   * Node Type Options.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $fields = [];

  /**
   * Formats.
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
    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $node_type) {
      $this->nodeTypeOptions[$node_type->id()] = $node_type->label();
    }
  }

  /**
   * Get the fields associated with selected node type.
   */
  protected function getFields() {
    $type = $this->conf->get('content_type');
    if (!empty($type)) {
      $nids = Drupal::entityQuery('node')
        ->condition('type', $this->conf->get('content_type'))
        ->range('0', '1')
        ->execute();
      $node = Node::load($nids[1]);
      $fieldDefinitions = array_keys($node->getFieldDefinitions());
      $this->fields[] = "None";
      foreach ($fieldDefinitions as $key) {
        $this->fields[] = $key;
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


    $form['import_files'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Import File(s)'),
      '#description' => $this->t('Enter the file name or names to import. Separate multiple files with a comma.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_files'),
      '#required' => TRUE,
    ];

    $form['content_type'] = [
      '#title' => $this->t('Content Type'),
      '#type' => 'select',
      '#description' => $this->t('Choose a content type into which to import stories.'),
      '#options' => $this->nodeTypeOptions,
      '#default_value' => $this->conf->get('content_type'),
    ];

    $type = $this->conf->get('content_type');
    if (!empty($type)) {
      $form['subhead_field'] = [
        '#title' => $this->t('Subhead field'),
        '#type' => 'select',
        '#description' => 'The Drupal field to use for the imported subhead.',
        '#options' => $this->fields,
        '#default_value' => $this->conf->get('subhead_field'),
      ];

      $form['byline_field'] = [
        '#type' => 'hidden',
        '#default_value' => $this->conf->get('byline_field'),
      ];
    }

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
      ->set('import_files', $form_state->getValue('import_files'))
      ->set('import_url', $form_state->getValue('import_url'))
      ->set('content_type', $form_state->getValue('content_type'))
      ->set('subhead_field', $form_state->getValue('subhead_field'))
      ->set('byline_field', $form_state->getValue('byline_field'))
      ->set('import_classifieds', $form_state->getValue('import_classifieds'))
      ->save();
  }

}