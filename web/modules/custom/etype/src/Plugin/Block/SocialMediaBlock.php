<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Social Media Block.
 *
 * @Block(
 *   id = "socialmedia_block",
 *   admin_label = @Translation("Social Media Block for Tiempos theme"),
 *   category = @Translation("eType"),
 * )
 */
class SocialMediaBlock extends BlockBase {

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
    $output = '';
    foreach ($links as $k => $v) {
      if (!empty($v)) {
        $output .= "<a href=\"$v\" class=\"has-text-grey-dark\" target=\"_blank\"><i class=\"fab fa-$k is-size-3\"></i></a>";
      }
    }
    return [
      '#children' => Markup::create($output),
    ];
  }

}
