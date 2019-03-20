<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a 'WillyWeather Widget' Block.
 *
 * @Block(
 *   id = "willyweatherwidget_block",
 *   admin_label = @Translation("WillyWeather Widget"),
 *   category = @Translation("eType"),
 * )
 */
class WillyWeatherWidgetBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {

    $config = \Drupal::config('etype.adminsettings');
    $willyweather_code = $config->get('willyweather_code');
    if (!empty($willyweather_code)) {
      $output = $willyweather_code;
    }
    else {
      $output = '<p>Please enter a WillyWeather URL at the eType Settings page to show MercoLocal content.</p>';
    }
    return [
      '#children' => Markup::create($output),
    ];
  }

}
