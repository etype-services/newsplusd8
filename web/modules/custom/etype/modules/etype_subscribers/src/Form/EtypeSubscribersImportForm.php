<?php

namespace Drupal\etype_subscribers\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EtypeSubscribersImportForm.
 *
 * @package Drupal\etype_subscribers\Form
 */
class EtypeSubscribersImportForm extends FormBase {

  public function getFormId() {
    return 'etype_subscribers_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['subscribers'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('CSV file of Subscribers'),
      '#upload_location' => 'public://',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Import Subscribers',
    );

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
