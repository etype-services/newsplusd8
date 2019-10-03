<?php

namespace Drupal\etype\views\Plugin\views\row;

use Drupal\views\Plugin\views\row\RssFields;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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
   * @return object
   *   Return Build.
   */
  public function render($row) {
    $build = parent::render();
    $item = $build['row'];

    $build['row'] = $item;
    return $build;
  }

}
