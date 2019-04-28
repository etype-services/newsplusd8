<?php

namespace Drupal\etype_wire_content\Form;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\NodeType;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Exception;

/**
 * Class WireConnectionException.
 *
 * @package Drupal\etype_wire_content\Form
 */
class WireConnectionException extends Exception {

  /**
   * WireConnectionException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('Wire database connection settings are missing. Please add them in this site’s settings.php.');
    parent::__construct($message);
  }

}
/**
 * Class EtypeWireContentConfigForm.
 *
 * @package Drupal\etype_wire_content\Form
 */
class EtypeWireContentConfigForm extends ConfigFormBase {

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
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityTypeManager;

  /**
   * The config settings.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $conf;

  /**
   * Node Types for select.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields attached to selected Node Type.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $fields = [];

  /**
   * Fieldname attached to taxonomy, to get sections.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $fieldName = '';

  /**
   * Sections to filter export.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $sections = [];

  /**
   * Any node, used to get info.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $node = [];

  /**
   * EtypeWireContentConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->messenger = Drupal::messenger();
    $this->conf = $this->config('etype_wire_content.settings');
    $this->entityFieldManager = Drupal::service('entity_field.manager');
    $this->entityTypeManager = Drupal::service('entity_type.manager');
    $nids = Drupal::entityQuery('node')
      ->condition('type', $this->conf->get('content_type'))
      ->range('0', '1')
      ->execute();
    $this->node = Node::load($nids[1]);
    $this->getnodeTypeOptions();
    $this->getFields();
    $this->getSections();
  }

  /**
   * Get node types and make options array.
   */
  protected function getnodeTypeOptions() {
    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $node_type) {
      $this->nodeTypeOptions[$node_type->id()] = $node_type->label();
    }
  }

  /**
   * Get the fields associated with selected node type.
   *
   * Apparently node::load is better than any entityFieldQuery.
   */
  protected function getFields() {
    $arr = array_keys($this->node->getFieldDefinitions());
    foreach ($arr as $key) {
      $this->fields[] = $key;
    }
  }

  /**
   * Get terms for related taxonomy.
   */
  protected function getSections() {
    kint($this->node);
    if (is_object($this->node)) {
      $arr = array_keys($this->node->getFieldDefinitions());
      $this->fieldName = $arr[$this->conf->get('field')];
      $term = Term::load($this->node->get($this->fieldName)->target_id);
      $vid = $term->bundle();
      $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);
      $term_data = [];
      foreach ($terms as $term) {
        $term_data[$term->tid] = $term->name;
      }
      $this->sections = $term_data;
    }
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
    return 'etype_wire_content_admin_form';
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
    $data = unserialize($result[0]->data);
    $options = $data['cluster'];

    $form['groups']['#markup'] = "Enable and edit import cron job at the <a href=\"/admin/config/system/cron/jobs/manage/etype_wire_content_cron\">cron settings page</a>.";

    /* Group settings. */
    $form['groups'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Groups'),
    ];

    $form['groups']['groups'] = [
      '#title' => t('Site Group(s)'),
      '#multiple' => TRUE,
      '#description' => t("The group(s) that this site belongs to. Sites can belong to one or more groups and will only show wire content from checked groups."),
      '#weight' => '1',
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $this->conf->get('groups') ?: [],
    ];

    $form['settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Other Settings'),
    ];

    $form['settings']['content_type'] = array(
      '#type' => 'select',
      '#title' => t('Choose which content type to import and export.'),
      '#default_value' => $this->conf->get('content_type') ?: 'article',
      '#options' => $this->nodeTypeOptions,
    );

    $form['settings']['field'] = array(
      '#type' => 'select',
      '#title' => t('Choose which field to use to filter exports.'),
      '#default_value' => $this->conf->get('field') ?: 'field_section',
      '#options' => $this->fields,
    );

    $form['settings']['sections'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Choose which taxonomy terms use to filter exports.'),
      '#default_value' => $this->conf->get('sections') ?: [],
      '#options' => $this->sections,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_wire_content.settings')
      ->set('groups', $form_state->getValue('groups'))
      ->set('content_type', $form_state->getValue('content_type'))
      ->set('field', $form_state->getValue('field'))
      ->set('fieldName', $this->fieldName)
      ->set('sections', $form_state->getValue('sections'))
      ->save();
  }

}