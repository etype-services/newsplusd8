<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Stay Tuned Header Block.
 *
 * @Block(
 *   id = "staytunedhcontact_block",
 *   admin_label = @Translation("Social Icon Block for Contact Page in News+ theme"),
 *   category = @Translation("eType"),
 * )
 */
class StayTunedContactBlock extends BlockBase {

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
    $output = '<h3 class="title">Stay tuned with us</h3>
<p>You can connect with us through our social media accounts by clicking on a logo.</p>
<ul class="icons-list large">';
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
