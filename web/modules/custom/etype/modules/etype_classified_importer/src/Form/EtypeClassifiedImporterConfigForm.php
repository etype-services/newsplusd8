<?php

namespace Drupal\etype_classified_importer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EtypeClassifiedImporterConfigForm.
 *
 * @package Drupal\etype_classified_importer\Form
 */
class EtypeClassifiedImporterConfigForm extends ConfigFormBase {

  /**
   * Config var.
   *
   * @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  protected $conf;

  /**
   * This is the eTypeClassifiedImporterConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_xml_importer.settings');
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_classified_importer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_classified_importer_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#markup'] = "Enable and edit import cron job at the <a href=\"/admin/config/system/cron/jobs/manage/etype_classified_importer_cron\">cron settings page</a>.";

    $form['import_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Import URL'),
      '#description' => $this->t('Enter the url of the file to import.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_url'),
      '#required' => TRUE,
    ];

    $form['classified_map'] = [
      '#title' => $this->t('Classifed Cateogry Mapping'),
      '#type' => 'textarea',
      '#description' => 'Enter VDATA Category Id and matching Classified Ad term id on one line, e.g C101|123.',
      '#default_value' => $this->conf->get('classified_map'),
      '#required' => TRUE,
    ];

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
    $this->config('etype_classified_importer.settings')
      ->set('import_url', $form_state->getValue('import_url'))
      ->set('classified_map', $form_state->getValue('classified_map'))
      ->save();
  }

}