<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Weather Widget Block.
 *
 * @Block(
 *   id = "weatherwidget_block",
 *   admin_label = @Translation("Weather Widget"),
 *   category = @Translation("eType"),
 * )
 */
class WeatherWidgetBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $config = \Drupal::config('etype.adminsettings');
    $weather_code = $config->get('weather_code');
    if (!empty($weather_code)) {
      $output = $weather_code;
    }
    else {
      $output = '<p>Please add weather code at the eType Settings page to show the weather widget.</p>';
    }
    return [
      '#children' => Markup::create($output),
    ];
  }

}
