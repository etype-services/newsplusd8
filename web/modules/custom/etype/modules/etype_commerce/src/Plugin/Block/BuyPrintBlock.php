<?php

namespace Drupal\etype_commerce\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Buy Print Block.
 *
 * @Block(
 *   id = "buy_print_block",
 *   admin_label = @Translation("Buy Print"),
 *   category = @Translation("eType"),
 * )
 */
class BuyPrintBlock extends BlockBase {

  public function build(): array {
    return \Drupal::formBuilder()->getForm('Drupal\etype_commerce\Form\BuyPrintForm');
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
