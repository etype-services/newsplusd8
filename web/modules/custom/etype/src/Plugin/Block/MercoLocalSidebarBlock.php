<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'MercoLocal Sidebar' Block.
 *
 * @Block(
 *   id = "mercolocalsidebar_block",
 *   admin_label = @Translation("Merco Local Sidebar"),
 *   category = @Translation("eType"),
 * )
 */
class MercoLocalSidebarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = \Drupal::config('etype.adminsettings');
    $mercolocal_id = $config->get('mercolocal_id');
    $output = '<var id="MercoLocal"><script id="MercoLocal-script" data-active="businesses" src="https://www.mercolocal.com/js/Embed.js?h=600&amp;w=300&amp;Scroll=v&amp;affiliateId=' .
      $mercolocal_id . '"></script></var>';
    return [
      '#markup' => $output,
    ];
  }

}
