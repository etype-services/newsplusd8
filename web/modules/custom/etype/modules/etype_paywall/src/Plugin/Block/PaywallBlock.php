<?php

namespace Drupal\etype_paywall\Plugin\Block;

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

    $output = '';
    if (isset($_COOKIE['paywallViewed'])) {
      $config = \Drupal::config('etype_paywall.settings');
      $freeNumber = $config->get('freeNumber');
      if ($_COOKIE['paywallViewed'] < $freeNumber) {
        $output = '<p>You have read ' . $_COOKIE['paywallViewed'] . ' of 4 free articles available this month.</p>';
      }
      else {
        $subLink = $config->get('subLink');
        if (empty($subLink)) {
          $e_editions = etype_e_editions();
          $subLink = $e_editions[0]['path'];
        }
        $output = '<p>You have read all your free articles for this period. Please <a href="' . $subLink . '">subscribe</a> to read more.</p>';
      }
    }

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
