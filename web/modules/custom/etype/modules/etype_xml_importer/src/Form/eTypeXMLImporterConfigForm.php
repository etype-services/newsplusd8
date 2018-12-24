<?php

/**
 * @file
 * Contains Drupal\etype\Form\eTypeConfigForm.
 */

namespace Drupal\etype_xml_importer\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class eTypeXMLImporterConfigForm extends ConfigFormBase {
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
    $config = $this->config('etype_xml_importer.settings');

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
      '#default_value' => $config->get('import_file'),
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
      '#default_value' => $config->get('import_url'),
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
      ->save();
  }

}