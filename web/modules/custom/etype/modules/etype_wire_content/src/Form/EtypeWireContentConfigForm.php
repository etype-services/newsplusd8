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
   * Field definitions for content type.
   *
   * @var EtypeWireContentConfigForm
   */
  protected $fieldDefinitions = [];

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
    $this->getnodeTypeOptions();
    $this->getSections();
    $this->getFields();
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
    foreach ($this->fieldDefinitions as $key) {
      $this->fields[] = $key;
    }
  }

  /**
   * Get terms for related taxonomy.
   */
  protected function getSections() {
    $fieldDefinitions = $this->conf->get('fieldDefinitions');
    $field = $this->conf->get('field');
    if (is_array($fieldDefinitions) && count($fieldDefinitions) > 0) {
      $this->fieldDefinitions = $fieldDefinitions;
    }
    else {
      /* First time loading form $this->conf->get('field') might not be set */
      if (!empty($field)) {
        $nids = Drupal::entityQuery('node')
          ->condition('type', $this->conf->get('nodeType'))
          ->range('0', '1')
          ->execute();
        $nid = reset($nids);
        if (isset($nid)) {
          if (is_object($this->node)) {
            $this->fieldDefinitions = array_keys($this->node->getFieldDefinitions());
          }
        }
      }
    }
    if (is_array($fieldDefinitions) && count($fieldDefinitions) > 0) {
      $this->fieldName = $this->fieldDefinitions[$field];
      $term = Term::load($this->node->get($this->fieldName)->target_id);
      if ($term != NULL) {
        $vid = $term->bundle();
        $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);
        $term_data = [];
        foreach ($terms as $term) {
          $term_data[$term->tid] = $term->name;
        }
        $this->sections = $term_data;
      }
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

    $form['settings']['nodeType'] = array(
      '#type' => 'select',
      '#title' => t('Choose which content type to import and export.'),
      '#default_value' => $this->conf->get('nodeType') ?: 'article',
      '#options' => $this->nodeTypeOptions,
    );

    $form['settings']['field'] = array(
      '#type' => 'select',
      '#title' => t('Choose which field to use to filter exports.'),
      '#default_value' => $this->conf->get('field') ?: 'field_section',
      '#options' => $this->fields,
    );

    if (count($this->sections) > 1) {
      $form['settings']['sections'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Choose which taxonomy terms use to filter exports.'),
        '#default_value' => $this->conf->get('sections'),
        '#options' => $this->sections,
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_wire_content.settings')
      ->set('groups', $form_state->getValue('groups'))
      ->set('nodeType', $form_state->getValue('nodeType'))
      ->set('field', $form_state->getValue('field'))
      ->set('fieldDefinitions', $this->fieldDefinitions)
      ->set('fieldName', $this->fieldName)
      ->set('sections', $form_state->getValue('sections'))
      ->save();
  }

}