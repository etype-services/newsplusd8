<?php

namespace Drupal\etype_pico\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Pico E-edition' Block.
 *
 * @Block(
 *   id = "etype_pico_block",
 *   admin_label = @Translation("Pico E-edition"),
 *   category = @Translation("eType"),
 * )
 */
class PicoEeditionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = 'Need iFrame Code';
    return [
      '#markup' => $output,
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
