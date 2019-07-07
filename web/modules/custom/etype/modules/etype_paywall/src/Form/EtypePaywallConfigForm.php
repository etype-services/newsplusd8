<?php

namespace Drupal\etype_paywall\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\etype_xml_importer\Form\EtypeXMLImporterConfigForm;
use Drupal\user\Entity\User;

/**
 * Class eTypeConfigForm.
 *
 * @package Drupal\etype\Form
 */
class EtypePaywallConfigForm extends ConfigFormBase {

  /**
   * Config.
   *
   * @var EtypePaywallConfigForm
   */
  protected $conf;

  /**
   * EtypePaywallConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_paywall.settings');
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_paywall.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_paywall_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['freeNumber'] = [
      '#title' => $this->t('Free articles'),
      '#description' => 'How many articles a site visitor can read for free.',
      '#type' => 'select',
      '#options' => ['4' => '4', '6' => '6', '10' => '10'],
      '#default_value' => $this->conf->get('freeNumber'),
    ];

    $form['expiresNumber'] = [
      '#title' => $this->t('Cookie expiration'),
      '#description' => 'How many days before the cookie expires and the user can read free articles again.',
      '#type' => 'select',
      '#options' => ['10' => '10', '20' => '20', '30' => '30'],
      '#default_value' => $this->conf->get('expiresNumber'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('etype_paywall.settings')
      ->set('freeNumber', $form_state->getValue('freeNumber'))
      ->set('expiresNumber', $form_state->getValue('expiresNumber'))
      ->save();

    Drupal::cache('menu')->invalidateAll();
  }

}