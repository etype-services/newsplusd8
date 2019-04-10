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
    $data = [];
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
      '#title' => $this->t('Global Groups'),
      '#description' => $this->t('Add or remove groups for all sites. Groups should be on one line, with machine_name and name separated by |.'),
      '#default_value' => $options,
      '#required' => TRUE,
    ];

    /* Section settings. */
    $form['sections'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Sections'),
    ];

    $form['sections']['sections'] = [
      '#type' => 'textfield',
      '#title' => t('Sections or Categories'),
      '#description' => $this->t('Article section tags to match using regex when exporting nodes to the wire database. Separated by |.'),
      '#default_value' => $data['sections'],
    ];

    $form['sections']['taxonomy'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Taxonomy Field'),
      '#description' => $this->t('The name of the field used to describe article sections or categories.'),
      '#default_value' => $data['taxonomy'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Form Submit.
   *
   * @param array $form
   *   Config Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Drupal Config Form.
   *
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /* Prepare form data for insert into wire db. */
    $data = [];
    $arr = [];
    $string = $form_state->getValue('groups');
    $split = preg_split("/\n/", $string);
    if (count($split) > 0) {
      foreach ($split as $item) {
        $tmp = explode("|", $item);
        $machine_name = trim($tmp[0]);
        if (!empty($machine_name)) {
          $name = trim($tmp[1]);
          $arr[$machine_name] = $name;
        }
      }
    }

    $data['cluster'] = $arr;
    $data['sections'] = $form_state->getValue('sections');
    $data['taxonomy'] = $form_state->getValue('taxonomy');
    $serialized = serialize($data);

    /* Connect to wire database and save settings. */
    Database::setActiveConnection('wire');
    $db = Database::getConnection();
    $db->update('settings')
      ->fields([
        'data' => $serialized,
      ])
      ->execute();
    /* Reset connection. */
    Database::setActiveConnection();

  }

}