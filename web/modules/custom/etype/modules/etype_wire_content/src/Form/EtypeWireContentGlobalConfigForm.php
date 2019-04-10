<?php

namespace Drupal\etype_wire_content\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Class EtypeWireContentGlobalConfigForm.
 *
 * @package Drupal\etype_wire_content\Form
 */
class EtypeWireContentGlobalConfigForm extends ConfigFormBase {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * The config settings.
   *
   * @var EtypeWireContentGlobalConfigForm
   */
  protected $conf;

  /**
   * Node Types for select.
   *
   * @var EtypeWireContentGlobalConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields attached to selected Node Type.
   *
   * @var EtypeWireContentGlobalConfigForm
   */
  protected $fields = [];

  /**
   * EtypeWireContentGlobalConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->messenger = Drupal::messenger();
    $this->conf = $this->config('etype_wire_content.settings');
    $this->entityFieldManager = Drupal::service('entity_field.manager');
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_wire_content.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_wire_content_global_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $check = _etype_wire_content_check_connection();
    /* throw Exception and return empty page with message if the wire database setings are missing */
    try {
      if ($check === 0) {
        throw new WireConnectionException();
      }
    }
    catch (WireConnectionException $e) {
      $this->messenger->addMessage($e->getMessage(), $this->messenger::TYPE_ERROR);
      return ['#markup' => ''];
    }

    /* Connect to wire database and get settings. */
    Database::setActiveConnection('wire');
    $db = Database::getConnection();
    $result = $db->select('settings', 's')->fields('s', ['data'])
      ->execute()
      ->fetchAll();
    /* Reset connection. */
    Database::setActiveConnection();

    /* Set Group Options */
    $options = '';
    if (isset($result[0]->data)) {
      $data = unserialize($result[0]->data);
      if (!empty($data['cluster'])) {
        $groups = $data['cluster'];
        foreach ($groups as $k => $v) {
          $options .= "$k|$v\n";
        }
      }
    }


    /* Group settings. */
    $form['groups'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Groups'),
    ];

    $form['groups']['groups'] = [
      '#type' => 'textarea',
      '#title' => t('Group Options'),
      '#description' => t('Add or remove group options for all sites. Options should be on one line, with machine_name and name separated by |.'),
      '#default_value' => $options,
      '#required' => TRUE,
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
  }

}