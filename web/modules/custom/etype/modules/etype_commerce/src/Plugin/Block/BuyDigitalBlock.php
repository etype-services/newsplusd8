<?php

namespace Drupal\etype_commerce\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Buy Digital Block.
 *
 * @Block(
 *   id = "buy_digital_block",
 *   admin_label = @Translation("Buy Digital"),
 *   category = @Translation("eType"),
 * )
 */
class BuyDigitalBlock extends BlockBase {

  /**
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build(): array {
    return \Drupal::formBuilder()->getForm('Drupal\etype_commerce\Form\BuyDigitalForm');
  }

  /**
   * Disable Caching.
   *
   * @return int
   *   Boolean
   */
  public function getCacheMaxAge(): int {
    return 0;
  }

}
