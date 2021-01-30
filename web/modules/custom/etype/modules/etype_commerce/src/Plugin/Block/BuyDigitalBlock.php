<?php

namespace Drupal\etype_commerce\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Buy Digital Block.
 *
 * @Block(
 *   id = "buy_digital_block",
 *   admin_label = @Translation("Buy Digital Subscription"),
 *   category = @Translation("eType"),
 * )
 */
class BuyDigitalBlock extends BlockBase {

  /**
   * @return array
   */
  public function build(): array {
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\etype_commerce\Form\BuyDigitalForm');
    $render['form_one'] = $builtForm;
    return $render;
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
