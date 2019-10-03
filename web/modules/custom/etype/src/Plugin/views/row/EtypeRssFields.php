<?php

namespace Drupal\etype\Plugin\views\row;

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
    dpm($item);
    $node = Node::load($row->nid);
    $item->image = file_create_url($node->field_image->entity->getFileUri());
    $build['#row'] = $item;
    return $build;
  }

}
