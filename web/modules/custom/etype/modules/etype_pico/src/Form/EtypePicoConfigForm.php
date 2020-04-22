<?php

namespace Drupal\etype_pico\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

/**
 * Class eTypePicoConfigForm.
 *
 * @package Drupal\etype\Form
 */
class EtypePicoConfigForm extends ConfigFormBase {

  /**
   * Array of Node Types, used to choose which to import into.
   *
   * @var eTypePicoConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * EtypePicoConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->getNodeTypeOptions();
  }

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
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('etype_pico.settings');

    $form['picoPublisherId'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pico Publisher Id'),
      '#size' => 55,
      '#default_value' => $config->get('picoPublisherId'),
    ];

    $form['nodeType'] = [
      '#title' => $this->t('Content Type'),
      '#type' => 'checkboxes',
      '#description' => $this->t('Choose the content type for restricted access.'),
      '#options' => $this->nodeTypeOptions,
      //'#default_value' => $config->get('nodeType'),
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
      ->set('nodeType', $form_state->getValue('nodeType'))
      ->save();
  }

}
