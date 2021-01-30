<?php

namespace Drupal\etype_commerce\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Buy Digital Block.
 *
 * @Block(
 *   id = "buy_print_block",
 *   admin_label = @Translation("Buy Print Subscription"),
 *   category = @Translation("eType"),
 * )
 */
class BuyPrintBlock extends BlockBase {

  /**
   * @return array
   */
  public function build(): array {
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\etype_commerce\Form\BuyPrintForm');
    $render['form_one'] = $builtForm;
    return $render;
  }

}
