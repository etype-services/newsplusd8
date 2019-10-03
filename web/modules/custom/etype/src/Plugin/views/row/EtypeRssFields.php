<?php

namespace Drupal\etype\Plugin\views\row;

use Drupal\views\Plugin\views\row\RssFields;

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

    $item->id = [
      'key' => 'id',
      'value' => $row->nid,
    ];
    $build['#row'] = $item;
    return $build;
  }

}
