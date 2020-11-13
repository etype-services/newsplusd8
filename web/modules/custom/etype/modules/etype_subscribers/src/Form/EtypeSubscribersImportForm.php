<?php

namespace Drupal\etype_subscribers\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EtypeSubscribersImportForm.
 */
class EtypeSubscribersImportForm extends FormBase {

  /**
   * Get Form Id.
   *
   * @return string
   *   Form Id
   */
  public function getFormId() {
    return 'etype_subscribers_import_form';
  }

  /**
   * Build the Form.
   *
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   *   Form
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['subscribers'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('CSV file of Subscribers'),
      '#upload_location' => 'public://',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Import Subscribers',
    ];

    return $form;
  }

  /**
   * Validate Form.
   *
   * @param array $form
   *   Form.
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
