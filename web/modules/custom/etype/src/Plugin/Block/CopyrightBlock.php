<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Copyright Block.
 *
 * @Block(
 *   id = "copyright_block",
 *   admin_label = @Translation("Copyright Notice"),
 *   category = @Translation("eType"),
 * )
 */
class CopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {
    $config = Drupal::config('system.site');
    $name = $config->get('name');
    $output = '<p class="has-text-centered">&copy;&nbsp;' . date("Y") . '&nbsp;' . $name . '</p>';
    return [
      '#children' => Markup::create($output),
    ];
  }

}
