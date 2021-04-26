<?php

namespace Drupal\etype\Plugin\views\row;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
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
   * Override Views Row to get custom RSS fields.
   *
   * @param mixed $row
   *   The row.
   *
   * @return array
   *   Return Build.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function render($row): array {
    $build = parent::render($row);
    $item = $build['#row'];
    $nid = $row->nid;
    $node = Node::load($nid);

    // Set the title.
    $item->title = $node->getTitle();

    // Set the image shareable url.
    if ($node->get('field_image')->target_id > 0) {
      $obj = File::load($node->get('field_image')->target_id);
      if (is_object($obj)) {
        $uri = $obj->getFileUri();
        $item->image = file_create_url($uri);
      }
    }

    // Set a better link.
    $item->link = $node->toUrl()->setAbsolute()->toString();

    // Fix guid link.
    $host = Drupal::request()->getSchemeAndHttpHost();
    $arr = [];
    foreach ($item->elements as $element) {
      if ($element['key'] !== 'guid') {
        if ($element['key'] == 'pubDate') {
          $date = date("D, d M Y H:i:s T", $node->created->value);
          $arr[] = [
            'key' => 'pubDate',
            'value' => $date,
          ];;
        }
        else {
          $arr[] = $element;
        }
      }
    }

    $item->elements = $arr;
    $item->elements[] = [
      'key' => 'guid',
      'value' => $host . "/node/" . $nid,
      'attributes' => ['isPermaLink' => FALSE],
    ];
    $build['#row'] = $item;
    return $build;
  }

}
