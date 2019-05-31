<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Date Block.
 *
 * @Block(
 *   id = "date_block",
 *   admin_label = @Translation("Date"),
 *   category = @Translation("eType"),
 * )
 */
class DateBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $output = '
<p class="is-size-7">' . date("F j, Y") . '</p>
<div><a role="button" class="navbar-burger is-hidden-desktop" aria-label="menu" aria-expanded="false"><i class="fas fa-bars"></i></a></div>
    ';

    return [
      '#children' => Markup::create($output),
    ];
  }

  /**
   * Disable Caching.
   *
   * @return int
   *   Boolean
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
