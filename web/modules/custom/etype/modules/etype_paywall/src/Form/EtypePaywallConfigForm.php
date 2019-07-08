<?php

namespace Drupal\etype_paywall\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

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
   * Array of Node Types, used to choose which to import into.
   *
   * @var EtypePaywallConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * EtypePaywallConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_paywall.settings');
    $this->getNodeTypeOptions();
  }

  /**
   * Get node types and make options array.
   */
  protected function getNodeTypeOptions() {
    $nodeTypes = NodeType::loadMultiple();
    foreach ($nodeTypes as $nodeType) {
      $this->nodeTypeOptions[$nodeType->id()] = $nodeType->label();
    }
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

    $form['nodeType'] = [
      '#title' => $this->t('Content Types'),
      '#type' => 'checkboxes',
      '#description' => $this->t('Choose content types to paywall.'),
      '#options' => $this->nodeTypeOptions,
      '#default_value' => $this->conf->get('nodeType') ?: [],
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
      ->set('nodeType', $form_state->getValue('nodeType'))
      ->save();

    Drupal::cache('menu')->invalidateAll();
  }

}
