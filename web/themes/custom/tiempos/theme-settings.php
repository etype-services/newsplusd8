<?php

/**
 * @file
 *
 * Tiempos theme settings.
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

  $form['tiempos_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tiempos Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['tiempos_settings']['inverted_logo'] = [
    '#type'     => 'managed_file',
    '#title'    => t('Inverted logo for Feature pages with dark background.'),
    '#required' => FALSE,
    '#upload_location' => file_default_scheme() . '://theme/',
    '#default_value' => theme_get_setting('inverted_logo'),
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg'],
    ],
  ];

  $form['#submit'][] = 'tiempos_settings_form_submit';
  $theme_handler = Drupal::service('theme_handler');
  $themes = $theme_handler->listInfo();
  $active_theme = $GLOBALS['theme_key'];
  $form_state['build_info']['files'][] = str_replace("/$active_theme.info", '', $themes[$active_theme]->filename) . '/theme-settings.php';

}

/**
 * Submit handler.
 *
 * @param array $form
 *   Form.
 * @param object $form_state
 *   Form.
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function tiempos_settings_form_submit(array &$form, $form_state) {
  $fid = $form_state['values']['inverted_logo'];
  $image = File::load($fid);
  if (is_object($image)) {
    $image->set('status', FILE_STATUS_PERMANENT);
    $image->save();
  }
}
