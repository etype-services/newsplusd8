<?php

namespace Drupal\etype_paywall\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Paywall Block.
 *
 * @Block(
 *   id = "paywall_block",
 *   admin_label = @Translation("Paywall Block"),
 *   category = @Translation("eType"),
 * )
 */
class PaywallBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $output = '<p>You have read ' . $_COOKIE['paywallViewed'] . ' of 4 free articles available this month.</p>';
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
