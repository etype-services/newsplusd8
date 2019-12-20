<?php

namespace Drupal\etype_newsware_importer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EtypeNewzWareImporterConfigForm.
 *
 * @package Drupal\etype_newzware_importer\Form
 */
class EtypeNewzWareImporterConfigForm extends ConfigFormBase {

  /**
   * Config var.
   *
   * @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  protected $conf;

  /**
   * This is the eTypeNewzWareImporterConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_newzware_importer.settings');
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_newzware_importer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_newzware_importer_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['help'] = [
      '#type' => 'item',
      '#markup' => t('Enable and edit import cron job at the <a href="/admin/config/system/cron/jobs/manage/etype_newzware_importer_cron">cron settings page</a>.'),
    ];

    $form['import_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Import URL'),
      '#description' => $this->t('Enter the url of the file to import.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('import_url'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_newzware_importer.settings')
      ->set('import_url', $form_state->getValue('import_url'))
      ->save();
  }

}
