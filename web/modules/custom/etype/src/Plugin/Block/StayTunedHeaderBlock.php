<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Stay Tuned Header Block.
 *
 * @Block(
 *   id = "staytunedheader_block",
 *   admin_label = @Translation("Social Icon Block for Header in News+ theme"),
 *   category = @Translation("eType"),
 * )
 */
class StayTunedHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $config = Drupal::config('etype.adminsettings');
    $links = [];
    $links['facebook'] = $config->get('facebook');
    $links['twitter'] = $config->get('twitter');
    $links['instagram'] = $config->get('instagram');
    $output = '<ul class="icons-list">';
    foreach ($links as $k => $v) {
      if (!empty($v)) {
        $output .= "<li><a href=\"$v\" target=\"_blank\"><i class=\"fa fa-$k\"></i></a><span class=\"sr-only\">" . ucfirst($k) . "</span></a></li>";
      }
    }
    $output .= '</ul>';
    return [
      '#children' => Markup::create($output),
    ];
  }

}
