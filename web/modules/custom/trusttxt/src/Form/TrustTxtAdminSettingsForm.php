<?php

namespace Drupal\trusttxt\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure trusttxt settings for this site.
 */
class TrustTxtAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'trusttxt_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['trusttxt.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('trusttxt.settings');

    $form['trusttxt_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Contents of trust.txt'),
      '#default_value' => $config->get('content'),
      '#cols' => 60,
      '#rows' => 20,
    ];

    $form['app_trusttxt_content'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Contents of app-trust.txt'),
      '#default_value' => $config->get('app_content'),
      '#cols' => 60,
      '#rows' => 20,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('trusttxt.settings');
    $config
      ->set('content', $form_state->getValue('trusttxt_content'))
      ->set('app_content', $form_state->getValue('app_trusttxt_content'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
