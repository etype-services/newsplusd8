<?php

namespace Drupal\etype_xml_importer\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
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
    $this->fields = getFields($this->conf->get('nodeType'));
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

    $form['help'] = [
      '#type' => 'item',
      '#markup' => t('Enable and edit importer cron job at the <a href="/admin/config/system/cron/jobs/manage/etype_xml_importer_cron">cron settings page</a>.'),
    ];

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
      '#description' => 'The field to use for the imported subhead.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('subheadField'),
    ];

    $form['imageField'] = [
      '#title' => $this->t('Image field'),
      '#type' => 'select',
      '#description' => 'The field to use for the imported image(s).',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('imageField'),
    ];

    $form['imageNumber'] = [
      '#title' => $this->t('Limit imported images'),
      '#description' => 'Match image field limit on Content Type.',
      '#type' => 'select',
      '#options' => ['Unlimited', '1'],
      '#default_value' => $this->conf->get('imageNumber'),
    ];

    $form['longCaptionField'] = [
      '#title' => $this->t('Long caption field'),
      '#type' => 'select',
      '#description' => 'The field to use for long captions.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('longCaptionField'),
    ];

    /*
    $taxonomy = $this->conf->get('taxonomy');
    $taxonomies = taxonomy_vocabulary_get_names();
    if (empty($taxonomy)) {
      $taxonomy = reset($taxonomies);
    }
    $form['taxonomy'] = [
      '#title' => $this->t('Taxonomy for sections'),
      '#type' => 'select',
      '#description' => 'The taxonomy containing the desired default section.',
      '#options' => $taxonomies,
      '#default_value' => $taxonomy,
    ];
    */

    $form['section'] = [
      '#title' => $this->t('Default section'),
      '#description' => 'Enter the section into which to import articles, ie "News".',
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default',
      /*'#selection_settings' => [
        'target_bundles' => [$taxonomy],
      ],*/
    ];
    $tid = $this->conf->get('section');
    if (!empty($tid)) {
      $term = Term::load($tid);
      $form['section']['#default_value'] = $term;
    }

    $form['sectionField'] = [
      '#title' => $this->t('Section field'),
      '#type' => 'select',
      '#description' => 'The field used to store the section.',
      '#options' => $this->fields,
      '#default_value' => $this->conf->get('sectionField'),
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

    $form['importAsPremium'] = [
      '#title' => $this->t('Imported nodes are Premium Content.'),
      '#type' => 'checkbox',
      '#default_value' => $this->conf->get('importAsPremium'),
    ];

    $form['importClassifieds'] = [
      '#title' => $this->t('Import classified section.'),
      '#type' => 'checkbox',
      '#default_value' => $this->conf->get('importClassifieds'),
    ];

    $form['deleteUnpublished'] = [
      '#title' => $this->t('Delete all unpublished nodes before importing.'),
      '#type' => 'checkbox',
      '#default_value' => $this->conf->get('deleteUnpublished'),
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
      ->set('fields', [])
      ->set('subheadField', $form_state->getValue('subheadField'))
      ->set('imageField', $form_state->getValue('imageField'))
      ->set('longCaptionField', $form_state->getValue('longCaptionField'))
      ->set('sectionField', $form_state->getValue('sectionField'))
      // ->set('taxonomy', $form_state->getValue('taxonomy'))
      ->set('section', $form_state->getValue('section'))
      ->set('imageNumber', $form_state->getValue('imageNumber'))
      ->set('importClassifieds', $form_state->getValue('importClassifieds'))
      ->set('deleteUnpublished', $form_state->getValue('deleteUnpublished'))
      ->set('importAsPremium', $form_state->getValue('importAsPremium'))
      ->set('author', $form_state->getValue('author'))
      ->save();
  }

}
