<?php

/**
 * @file
 */

use Drupal\file\Entity\File;

/**
 * Add themes settings.
 *
 * @param array $form
 *   Form array.
 * @param object $form_state
 *   Form state array.
 */
function tiempos_form_system_theme_settings_alter(array &$form, &$form_state) {

  $file_default_scheme = Drupal::config('system.file')->get('default_scheme');

  $form['tiempos_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('Tiempos Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  ];

  $form['tiempos_settings']['inverted_logo'] = [
    '#type'     => 'managed_file',
    '#title'    => t('Inverted logo for Spotlight pages with dark background.'),
    '#required' => FALSE,
    '#upload_location' => $file_default_scheme . '://theme/',
    '#default_value' => theme_get_setting('inverted_logo'),
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg'],
    ],
  ];

  $form['#submit'][] = 'tiempos_settings_form_submit';

}

/**
 * Implements hook_settings_form_submit().
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function tiempos_settings_form_submit(array &$form, $form_state) {
  $fid = $form_state->getValue('inverted_logo');
  if (count($fid) > 0) {
    $image = File::load($fid[0]);
    $image->setPermanent();
    $image->save();
    $file_usage = Drupal::service('file.usage');
    $file_usage->add($image, 'tiempos', 'file', $fid[0]);
  }
}
