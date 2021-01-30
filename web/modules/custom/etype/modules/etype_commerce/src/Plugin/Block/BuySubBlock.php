<?php

namespace Drupal\etype_commerce\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Buy Digital Block containing the Print and Digital blocks.
 *
 * @Block(
 *   id = "buy_sub_block",
 *   admin_label = @Translation("Buy Subscriptions"),
 *   category = @Translation("eType"),
 * )
 */
class BuySubBlock extends BlockBase {

  /**
   * @return array
   */
  public function build(): array {
    return [
      '#theme' => 'buy_sub',
    ];
  }

}
