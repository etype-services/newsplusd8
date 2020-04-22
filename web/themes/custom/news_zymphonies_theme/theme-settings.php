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
function news_zymphonies_theme_form_system_theme_settings_alter(array &$form, $form_state) {

  $form['news_zymphonies_theme_info'] = [
    '#markup' => <<<EOF
    <h2>Advanced Theme Settings</h2>
EOF,
  ];

  $form['news_zymphonies_theme_settings']['custom'] = [
    '#type' => 'details',
    '#title' => t('Various Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];

  $form['news_zymphonies_theme_settings']['custom']['header_left_class'] = [
    '#type' => 'textfield',
    '#title' => t('Header Left (logo area) class'),
    '#default_value' => theme_get_setting('header_left_class', 'news_zymphonies'),
    '#size' => 12,
    '#maxlength' => 12,
  ];

  $form['news_zymphonies_theme_settings']['custom']['header_right_class'] = [
    '#type' => 'textfield',
    '#title' => t('Header Right (advertisement) class'),
    '#default_value' => theme_get_setting('header_right_class', 'news_zymphonies'),
    '#size' => 12,
    '#maxlength' => 12,
  ];

  // Social Icon Link.
  $form['news_zymphonies_theme_settings']['social_icon'] = [
    '#type' => 'details',
    '#title' => t('Social Media Link'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['show_social_icon'] = [
    '#type' => 'checkbox',
    '#title' => t('Show Social Icons'),
    '#default_value' => theme_get_setting('show_social_icon'),
    '#description' => t("Show/Hide Social media links"),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['facebook_url'] = [
    '#type' => 'textfield',
    '#title' => t('Facebook URL'),
    '#default_value' => theme_get_setting('facebook_url'),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['google_plus_url'] = [
    '#type' => 'textfield',
    '#title' => t('Google plus URL'),
    '#default_value' => theme_get_setting('google_plus_url'),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['twitter_url'] = [
    '#type' => 'textfield',
    '#title' => t('Twitter URL'),
    '#default_value' => theme_get_setting('twitter_url'),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['linkedin_url'] = [
    '#type' => 'textfield',
    '#title' => t('LinkedIn URL'),
    '#default_value' => theme_get_setting('linkedin_url'),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['pinterest_url'] = [
    '#type' => 'textfield',
    '#title' => t('Pinterest URL'),
    '#default_value' => theme_get_setting('pinterest_url'),
  ];
  $form['news_zymphonies_theme_settings']['social_icon']['rss_url'] = [
    '#type' => 'textfield',
    '#title' => t('RSS URL'),
    '#default_value' => theme_get_setting('rss_url'),
  ];

  // Show/Hide credit.
  $form['news_zymphonies_theme_settings']['credit_link'] = [
    '#type' => 'details',
    '#title' => t('Footer Credit Link'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];
  $form['news_zymphonies_theme_settings']['credit_link']['show_credit_link'] = [
    '#type' => 'checkbox',
    '#title' => t("Show/Hide 'Designed by Zymphonies' credit text"),
    '#default_value' => theme_get_setting('show_credit_link'),
    '#description' => t("Highly recomend to display credit in footer"),
  ];

  $form['news_zymphonies_theme_settings']['slideshow'] = [
    '#type' => 'details',
    '#title' => t('Front Page Slideshow'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];
  $form['news_zymphonies_theme_settings']['slideshow']['no_of_slides'] = [
    '#type' => 'textfield',
    '#title' => t('Number of slides'),
    '#default_value' => theme_get_setting('no_of_slides'),
    '#description' => t("Enter the number of slides required & Save configuration"),
    '#markup' => '<div class="messages messages--warning">Clear caches after making any changes in theme settings. <a href="../../config/development/performance">Click here to clear cashe</a></div>',
  ];
  $form['news_zymphonies_theme_settings']['slideshow']['show_slideshow'] = [
    '#type' => 'checkbox',
    '#title' => t('Show Slideshow'),
    '#default_value' => theme_get_setting('show_slideshow'),
    '#description' => t("Show/Hide Slideshow in home page"),
  ];
  $form['news_zymphonies_theme_settings']['slideshow']['slide'] = [
    '#markup' => t('Change the banner image, title, description and link using below fieldset'),
  ];

  $slides = theme_get_setting('no_of_slides');
  for ($i = 1; $i <= $slides; $i++) {

    $form['news_zymphonies_theme_settings']['slideshow']['slide' . $i] = [
      '#type' => 'details',
      '#title' => t("Slide @i", ['@i' => $i]),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['news_zymphonies_theme_settings']['slideshow']['slide' . $i]['slide_image_path' . $i] = [
      '#type' => 'managed_file',
      '#title' => t('Slide @i Image', ['@i' => $i]),
      '#default_value' => theme_get_setting('slide_image_path' . $i, 'news_zymphonies_theme'),
      '#upload_location' => 'public://',
    ];
    $form['news_zymphonies_theme_settings']['slideshow']['slide' . $i]['slide_title_' . $i] = [
      '#type' => 'textfield',
      '#title' => t('Slide @i Title', ['@i' => $i]),
      '#default_value' => theme_get_setting('slide_title_' . $i, 'news_zymphonies_theme'),
    ];
    $form['news_zymphonies_theme_settings']['slideshow']['slide' . $i]['slide_description_' . $i] = [
      '#type' => 'textarea',
      '#title' => t('Slide @i Description', ['@i' => $i]),
      '#default_value' => theme_get_setting('slide_description_' . $i, 'news_zymphonies_theme'),
    ];
    $form['news_zymphonies_theme_settings']['slideshow']['slide' . $i]['slide_url_' . $i] = [
      '#type' => 'textfield',
      '#title' => t('Slide @i URL', ['@i' => $i]),
      '#default_value' => theme_get_setting('slide_url_' . $i, 'news_zymphonies_theme'),
    ];

  }
}
