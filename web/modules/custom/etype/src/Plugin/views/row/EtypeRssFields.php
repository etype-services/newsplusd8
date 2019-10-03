<?php

namespace Drupal\views\Plugin\views\row;

/**
 * Renders an RSS item based on fields.
 *
 * @ViewsRow(
 *   id = "rss_fields",
 *   title = @Translation("Fields"),
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
   */
  public function render($row) {
    parent::render();
  }

}
