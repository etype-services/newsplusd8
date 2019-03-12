<?php

/**
 * @file
 * Contains Drupal\etype\Form\eTypeConfigForm.
 */

namespace \Drupal\etype\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class eTypeConfigForm.
 *
 * @package Drupal\etype\Form
 */
class eTypeConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('etype.adminsettings');

    $form['e_edition'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('e-Edition'),
    ];

    $form['e_edition']['etype_e_edition'] = [
      '#type' => 'textarea',
      '#title' => $this->t('e-Edition'),
      '#description' => $this->t('For one paper enter the e-Edition like <code>Mitchell%20News-JournalID617</code>, for more than one format like this: <code>Mitchell%20News-JournalID617|Mitchell News Journal,The%20Yorktown%20News-ViewID84|The Yorktown News View</code>'),
      '#size' => 101,
      '#default_value' => $config->get('etype_e_edition'),
    ];

    $form['e_edition']['etype_pub'] = [
      '#type' => 'textfield',
      '#title' => $this->t('eType Pub Id'),
      '#description' => $this->t('Separate multiple entries with a comma, in the same order as the e-Editions'),
      '#size' => 55,
      '#default_value' => $config->get('etype_pub'),
    ];

    $form['e_edition']['etype_ptype'] = [
      '#type' => 'textfield',
      '#title' => $this->t('eType PType'),
      '#description' => $this->t('Separate multiple entries with a comma, in the same order as the e-Editions'),
      '#size' => 55,
      '#default_value' => $config->get('etype_ptype'),
    ];


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

    $this->config('etype.adminsettings')
      ->set('etype_e_edition', $form_state->getValue('etype_e_edition'))
      ->set('etype_pub', $form_state->getValue('etype_pub'))
      ->set('etype_ptype', $form_state->getValue('etype_ptype'))
      ->save();
  }

}