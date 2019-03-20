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
   * hook_page_attachments gives a console error.
   */
  public function build() {

    $config = \Drupal::config('etype.adminsettings');
    $mercolocal_id = $config->get('mercolocal_id');
    if (!empty($mercolocal_id)) {
      $output = '<var id="MercoLocal">
<script id="MercoLocal-script" data-active="businesses" src="https://www.mercolocal.com/js/Embed.js?h=600&amp;w=300&amp;Scroll=v&amp;affiliateId=' .
        $mercolocal_id . '"></script>
</var>';
    }
    else {
      $output = '<p>Please enter a MercoLocal Affiliate Id at the eType Settings page to show MercoLocal content.</p>';
    }
    return [
      '#children' => Markup::create($output),
    ];
  }

}
