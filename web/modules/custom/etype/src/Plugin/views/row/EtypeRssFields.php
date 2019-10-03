<?php

namespace Drupal\etype\Plugin\views\row;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\row\RssFields;
use Drupal\node\Entity\Node;

/**
 * Renders an RSS item based on fields.
 *
 * @ViewsRow(
 *   id = "etype_rss_fields",
 *   title = @Translation("eType RSS Fields"),
 *   help = @Translation("Display fields as RSS items."),
 *   theme = "views_view_row_rss",
 *   display_types = {"feed"}
 * )
 */
class EtypeRssFields extends RssFields {

  /**
   * Override Views Row.
   *
   * @param mixed $row
   *   The row.
   *
   * @return array
   *   Return Build.
   */
  public function render($row) {
    $build = parent::render($row);
    $item = $build['#row'];
    $node = Node::load($row->nid);
    $build['#row'] = $item;
    return $build;
  }

  /**
   * Override buildOptionsForm.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form State.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $initial_labels = ['' => $this->t('- None -')];
    $view_fields_labels = $this->displayHandler->getFieldLabels();
    $view_fields_labels = array_merge($initial_labels, $view_fields_labels);
    $form['image_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Image field'),
      '#description' => $this->t('The field that is going to be used as the RSS item image for each row.'),
      '#options' => $view_fields_labels,
      '#default_value' => $this->options['image_field'],
      '#required' => TRUE,
    ];
  }

}
