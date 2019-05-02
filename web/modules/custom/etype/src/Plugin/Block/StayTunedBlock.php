<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Stay Tuned Block.
 *
 * @Block(
 *   id = "staytuned_block",
 *   admin_label = @Translation("Social Media Icon Block for News+ theme"),
 *   category = @Translation("eType"),
 * )
 */
class StayTunedBlock extends BlockBase {

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
    $output = '<ul class="icons-list">';
    foreach ($links as $k => $v) {
      if (!empty($v)) {
        $output .= "<li><a href=\"$v\"><i class=\"fa fa-$k\"></i></a><span class=\"sr-only\">" . ucfirst($k) . "</span></a></li>";
      }
    }
    $output .= '</ul>';
    return [
      '#children' => Markup::create($output),
    ];
  }

}
