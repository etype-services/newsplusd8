<?php

/**
 * @file
 * Theme Settings.
 */

/**
 * Add themes settings.
 *
 * @param array $form
 *   Form array.
 * @param object $form_state
 *   Form state array.
 */
function news_zymphonies_form_system_theme_settings_alter(array &$form, &$form_state) {

  $form['news_zymphonies_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('News Zymphonies Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  ];

  $form['news_zymphonies_settings']['header_left_class'] = [
    '#type' => 'textfield',
    '#title' => t('Header Left (logo area) class'),
    '#default_value' => theme_get_setting('header_left_class', 'news_zymphonies'),
    '#size'          => 12,
    '#maxlength'     => 12,
  ];

  $form['news_zymphonies_settings']['header_right_class'] = [
    '#type' => 'textfield',
    '#title' => t('Header Right (advertisement) class'),
    '#default_value' => theme_get_setting('header_right_class', 'news_zymphonies'),
    '#size'          => 12,
    '#maxlength'     => 12,
  ];

  $form['#submit'][] = 'news_zymphonies_settings_form_submit';

}
