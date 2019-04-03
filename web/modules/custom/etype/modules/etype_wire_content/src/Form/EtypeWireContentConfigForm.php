<?php

namespace Drupal\etype_wire_content\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\NodeType;
use Exception;

/**
 * Class WireConnectionException.
 *
 * @package Drupal\etype_wire_content\Form
 */
class WireConnectionException extends Exception {

  /**
   * WireConnectionException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('Wire database connection settings are missing.');
    parent::__construct($message);
  }

}
/**
 * Class EtypeWireContentConfigForm.
 *
 * @package Drupal\etype_wire_content\Form
 */
class EtypeWireContentConfigForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * The config settings.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $conf;

  /**
   * Node Types for select.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields attached to selected Node Type.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $fields = [];

  /**
   * EtypeWireContentConfigForm constructor.
   */
  public function __construct() {
    /* throw Exception and return empty page with message if the wire database setings are missing */
    try {
      $check = _etype_wire_check_connection();
      if ($check === FALSE) {
        throw new WireConnectionException();
      }
    }
    catch (WireConnectionException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_wire_content.settings');
    $this->entityFieldManager = \Drupal::service('entity_field.manager');
    $this->getnodeTypeOptions();
    $this->getFields();
  }

  /**
   * Get node types and make options array.
   */
  protected function getnodeTypeOptions() {
    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $node_type) {
      $this->nodeTypeOptions[$node_type->id()] = $node_type->label();
    }
  }

  /**
   * Get the fields associated with selected node type.
   */
  protected function getFields() {
    $fields = $this->entityFieldManager->getFieldDefinitions('node', $this->conf->get('node_type'));
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
      'etype_wire_content.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_wire_content_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /* importer settings */
    $form['wire_content'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic configuration'),
    ];

    $form['wire_content']['#markup'] = "Enable and edit import cron job at the <a href=\"/admin/config/system/cron/jobs/manage/etype_wire_content_cron\">cron settings page</a>.";


    $form['wire_content']['import_files'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Import File(s)'),
      '#description' => $this->t('Enter the file name or names to import. Separate multiple files with a comma.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_files'),
      '#required' => TRUE,
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
    $this->config('etype_wire_content.settings')
      ->set('import_files', $form_state->getValue('import_files'))
      ->save();
  }

}