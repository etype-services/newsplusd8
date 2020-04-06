<?php

namespace Drupal\etype\Plugin\views\style;

use Drupal\views\Plugin\views\style\Rss;

/**
 * This is the eType style plugin to render an RSS feed.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "etype_rss",
 *   title = @Translation("eType RSS Feed"),
 *   help = @Translation("Generates an RSS feed from a view."),
 *   theme = "views_view_rss",
 *   display_types = {"feed"}
 * )
 */
class EtypeRss extends Rss {

  /**
   * Override Views Rss styles.
   *
   * @return array
   *   Return Build.
   */
  public function render() {

    if (empty($this->view->rowPlugin)) {
      trigger_error('Drupal\views\Plugin\views\style\Rss: Missing row plugin', E_WARNING);
      return [];
    }
    $rows = [];

    $this->namespaces = ['xmlns:dc' => 'http://purl.org/dc/elements/1.1/'];

    foreach ($this->view->result as $row_index => $row) {
      $this->view->row_index = $row_index;
      $rows[] = $this->view->rowPlugin->render($row);
    }

    $build = [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#options' => $this->options,
      '#rows' => $rows,
    ];
    unset($this->view->row_index);
    return $build;
  }

}
