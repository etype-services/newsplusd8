<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

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
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $config = \Drupal::config('etype.adminsettings');
    $mercolocal_id = $config->get('mercolocal_id');
    if (!empty($mercolocal_id)) {
      $output = '<var id="MercoLocal"></var>';
    }
    else {
      $output = '<p>Please enter a MercoLocal Affiliate Id at the eType Settings page to show MercoLocal content.</p>';
    }
    return [
      '#children' => Markup::create($output),
    ];
  }

}
