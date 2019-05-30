<?php

namespace Drupal\etype_xml_importer\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\User;
use Drupal\taxonomy\Entity\Term;

/**
 * Class EtypeXMLImporterConfigForm.
 *
 * @package Drupal\etype_xml_importer\Form
 */
class EtypeXMLImporterConfigForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * Config.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $conf;

  /**
   * Array of Node Types, used to choose which to import into.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $nodeTypeOptions = [];

  /**
   * Fields, array of field names associated with selected content type.
   *
   * @var EtypeXMLImporterConfigForm
   */
  protected $fields = [];

  /**
   * EtypeXMLImporterConfigForm constructor.
   */
  public function __construct() {
    parent::__construct($this->configFactory());
    $this->conf = $this->config('etype_xml_importer.settings');
    $this->entityFieldManager = Drupal::service('entity_field.manager');
    $this->getNodeTypeOptions();
    $this->getFields();
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
   * Get the fields associated with selected node type.
   */
  protected function getFields() {
    /* fields is array of names of fields in nodeType */
    $fields = $this->conf->get('fields');
    /* If set, use setting. */
    if (is_array($fields) && count($fields) > 0 && array_key_exists("None", $fields)) {
      $this->fields = $fields;
    }
    else {
      /* Check for nodeType. If it exists load a node */
      /* Use that to get nodeType FieldDefinitions */
      /* fields is array of FieldDefinitions keys */
      /* Used to build options array to select subhead fields for import. */
      $type = $this->conf->get('nodeType');
      if (!empty($type)) {
        $nids = Drupal::entityQuery('node')
          ->condition('type', $type)
          ->range('0', '1')
          ->execute();
        $nid = reset($nids);
        /* Code is based on existence of articles, so a bug for new/empty sites. */
        if (isset($nid) && $nid > 0) {
          $node = Node::load($nid);
          $fieldDefinitions = array_keys($node->getFieldDefinitions());
          $this->fields["None"] = "None";
          foreach ($fieldDefinitions as $key) {
            $this->fields[$key] = $key;
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype_xml_importer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_xml_importer_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $taxonomy = empty($this->conf->get('taxonomy')) ? 'sections' : $this->conf->get('taxonomy');

    $form['#markup'] = "Enable and edit import cron job at the <a href=\"/admin/config/system/cron/jobs/manage/etype_xml_importer_cron\">cron settings page</a>.";


    $form['importUrls'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Import Urls(s)'),
      '#description' => $this->t('Enter the full url or urls from which to import XML. Put multiple files on separate lines.'),
      '#size' => 55,
      '#default_value' => $this->conf->get('importUrls'),
      '#required' => TRUE,
    ];

    $form['nodeType'] = [
      '#title' => $this->t('Content Type'),
      '#type' => 'select',
      '#description' => $this->t('Choose a content type into which to import stories.'),
      '#options' => $this->nodeTypeOptions,
      '#default_value' => $this->conf->get('nodeType'),
    ];

    $form['subheadField'] = [
      '#title' => $this->t('Subhead field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported subhead.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('subheadField'),
    ];

    $form['imageField'] = [
      '#title' => $this->t('Image field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the imported image(s).',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('imageField'),
    ];

    $form['longCaptionField'] = [
      '#title' => $this->t('Long caption field'),
      '#type' => 'select',
      '#description' => 'The Drupal field to use for the long caption.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('longCaptionField'),
    ];

    $form['taxonomy'] = [
      '#title' => $this->t('Taxonomy for sections'),
      '#type' => 'text_field',
      '#description' => 'The Taxonomy to use for the sectionsn.',
      '#default_value' => $taxonomy,
    ];

    $form['section'] = [
      '#title' => $this->t('Section'),
      '#description' => 'Enter the section into which to import articles, ie "News".',
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => [$taxonomy],
      ],
    ];
    $tid = $this->conf->get('section');
    if (!empty($tid)) {
      $term = Term::load($tid);
      $form['section']['#default_value'] = $term;
    }

    $form['imageNumber'] = [
      '#title' => $this->t('Limit Imported Images'),
      '#description' => 'Match image field limit on Content Type.',
      '#type' => 'select',
      '#options' => ['Unlimited', '1'],
      '#default_value' => $this->conf->get('imageNumber'),
    ];

    $form['author'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Default Author'),
      '#size' => 30,
      '#maxlength' => 60,
      '#target_type' => 'user',
    ];

    $uid = $this->conf->get('author');
    if ($uid > 0) {
      $author = User::load($uid);
      $form['author']['#default_value'] = $author;
    }

    $form['importClassifieds'] = [
      '#title' => $this->t('Check to import Olive classified section.'),
      '#type' => 'checkbox',
      '#default_value' => $this->conf->get('importClassifieds'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    /* check for trailing slash on import_url */
    $val = $form_state->getValue('import_url');
    if (substr($val, -1) !== '/') {
      $val = $val . '/';
      $form_state->setValue('import_url', $val);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('etype_xml_importer.settings')
      ->set('importUrls', $form_state->getValue('importUrls'))
      ->set('nodeType', $form_state->getValue('nodeType'))
      ->set('fields', $this->fields)
      ->set('subheadField', $form_state->getValue('subheadField'))
      ->set('imageField', $form_state->getValue('imageField'))
      ->set('longCaptionField', $form_state->getValue('longCaptionField'))
      ->set('taxonomy', $form_state->getValue('taxonomy'))
      ->set('section', $form_state->getValue('section'))
      ->set('imageNumber', $form_state->getValue('imageNumber'))
      ->set('importClassifieds', $form_state->getValue('importClassifieds'))
      ->set('author', $form_state->getValue('author'))
      ->save();
  }

}