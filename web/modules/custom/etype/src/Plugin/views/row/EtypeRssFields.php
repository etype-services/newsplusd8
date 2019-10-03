<?php

namespace Drupal\etype\Plugin\views\row;

use Drupal\views\Plugin\views\row\RssFields;
use stdClass;

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
    $item = new stdClass();
    $item->id = $row->nid;
    $item->title = 'Test';
    $build = [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#options' => $this->options,
      '#row' => $item,
      '#field_alias' => isset($this->field_alias) ? $this->field_alias : '',
    ];
    return $build;
  }

}
