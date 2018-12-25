<?php

/**
 * @file
 * Contains Drupal\etype\Form\eTypeConfigForm.
 */

namespace Drupal\etype_xml_importer\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

class eTypeXMLImporterConfigForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entity_field_manager;

  /**
   * @var
   */
  protected $conf;

  /**
   * @var
   */
  protected $node_type_options = [];

  /**
   * @var
   */
  protected $fields = [];

  /**
   * eTypeXMLImporterConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_xml_importer.settings');
    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $node_type) {
      $this->node_type_options[$node_type->id()] = $node_type->label();
    }
    $this->getFields();
  }


  /**
   *
   */
  protected function getFields() {
    $this->entity_field_manager = \Drupal::service('entity_field.manager');
    $fields = $this->entity_field_manager->getFieldDefinitions('node', $this->conf->get('node_type'));
    $arr = array_keys($fields);
    foreach ($arr as $key) {
      $this->fields[] = $key;
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

    /* importer settings */
    $form['importer'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Basic configuration'),
    );

    $form['importer']['import_file'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Import File'),
      '#description' => $this->t('Enter the file name or names to import. Separate multiple files with a comma.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_file'),
    );

    $form['importer']['subhead_field'] = array(
      '#title' => $this->t('Subhead field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported subhead.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('subhead_field'),
    );

    $form['importer']['byline_field'] = array(
      '#title' => $this->t('Byline field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported byline.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('byline_field'),
    );


    /* advanced importer settings */
    $form['importer_advanced'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Advanced configuration'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $form['importer_advanced']['import_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Base Import Url'),
      '#description' => $this->t('Url from which to import xml.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_url'),
    );

    $form['importer_advanced']['node_type'] = array(
      '#title' => $this->t('Content Type'),
      '#type' => 'select',
      '#description' => $this->t('Choose a content type into which to import stories.'),
      '#options' => $this->node_type_options,
      '#default_value' => $this->conf->get('node_type'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_xml_importer.settings')
      ->set('import_file', $form_state->getValue('import_file'))
      ->set('import_url', $form_state->getValue('import_url'))
      ->set('node_type', $form_state->getValue('node_type'))
      ->set('subhead_field', $form_state->getValue('subhead_field'))
      ->set('byline_field', $form_state->getValue('byline_field'))
      ->save();
  }

}