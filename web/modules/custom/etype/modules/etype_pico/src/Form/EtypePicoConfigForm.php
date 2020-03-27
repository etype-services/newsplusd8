<?php

namespace Drupal\etype_pico\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class eTypePicoConfigForm.
 *
 * @package Drupal\etype\Form
 */
class EtypePicoConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_pico.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_pico_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('etype_pico.settings');

    $form['e_edition']['picoPublisherId'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pico Publisher Id'),
      '#size' => 55,
      '#default_value' => $config->get('picoPublisherId'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_pico.settings')
      ->set('picoPublisherId', $form_state->getValue('picoPublisherId'))
      ->save();
  }

}
