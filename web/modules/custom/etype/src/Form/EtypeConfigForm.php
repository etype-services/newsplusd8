<?php

namespace Drupal\etype\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Class eTypeConfigForm.
 *
 * @package Drupal\etype\Form
 */
class EtypeConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'etype.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('etype.adminsettings');
    $uid = $config->get('author');
    $message = $config->get('premium_content_message');
    $premium_content_message = !is_array($message) ? ['value' => "Massage", 'format' => "basic_html"] : $message;
    $author = '';
    if ($uid > 0) {
      $author = User::load($uid);
    }

    $v2 = 0;
    $moduleHandler = \Drupal::service('module_handler');
    if ($moduleHandler->moduleExists('etype_login_v2')) {
      $v2 = 1;
    }

    $form['e_edition'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('e-Edition'),
    ];

    $form['e_edition']['etype_pub'] = [
      '#type' => 'textfield',
      '#title' => $this->t('eType Pub Id'),
      '#description' => $this->t('Separate multiple entries with a comma, in the same order as the e-Editions.'),
      '#size' => 55,
      '#default_value' => $config->get('etype_pub'),
    ];

    if ($v2 == 0) {
      $form['e_edition']['etype_e_edition'] = [
        '#type' => 'textarea',
        '#title' => $this->t('e-Edition'),
        '#description' => $this->t('For one publication enter the e-Edition like <code>Mitchell%20News-JournalID617</code>, for multiple publications, please format like this: <code>Mitchell%20News-JournalID617|Mitchell News Journal,The%20Yorktown%20News-ViewID84|The Yorktown News View</code>.'),
        '#rows' => 2,
        '#cols' => '100',
        '#default_value' => $config->get('etype_e_edition'),
      ];

      $form['e_edition']['etype_ptype'] = [
        '#type' => 'textfield',
        '#title' => $this->t('eType PType'),
        '#description' => $this->t('Separate multiple entries with a comma, in the same order as the e-Editions.'),
        '#size' => 55,
        '#default_value' => $config->get('etype_ptype'),
      ];
    }

    $form['e_edition']['etype_author_links_off'] = [
      '#title' => $this->t('Check this box to turn author links off.'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('etype_author_links_off'),
    ];

    $form['e_edition']['premium_content_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Premium Content Message'),
      '#description' => $this->t('Message shown to unauthenticated users attempting to access premium content nodes.'),
      '#cols' => '100',
      '#default_value' => $premium_content_message['value'],
      '#format' => $premium_content_message['format'],
    ];

    $form['e_edition']['premium_preview'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Premium Preview'),
      '#description' => $this->t('Enter the number of characters to display before the log in message for Premium Content.'),
      '#size' => 55,
      '#default_value' => $config->get('premium_preview'),
    ];

    $form['social'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Social Media'),
    ];

    $form['social']['facebook'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook URL'),
      '#description' => $this->t('Enter the complete web address.'),
      '#size' => 55,
      '#default_value' => $config->get('facebook'),
    ];

    $form['social']['twitter'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter URL'),
      '#description' => $this->t('Enter the complete web address.'),
      '#size' => 55,
      '#default_value' => $config->get('twitter'),
    ];

    $form['social']['instagram'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Instagram URL'),
      '#description' => $this->t('Enter the complete web address.'),
      '#size' => 55,
      '#default_value' => $config->get('instagram'),
    ];

    $form['other'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Other Settings'),
    ];

    $form['other']['weather_code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Weather Code'),
      '#description' => $this->t('Paste in weather widget code. For WillyWeather code, please just change the id, at the end of the iframe src attribute.'),
      '#cols' => '100',
      '#default_value' => $config->get('weather_code'),
    ];

    $form['other']['head_script'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Header Scripts'),
      '#description' => $this->t('Any code pasted in here will be added to the end of the head section of the site. Please note that third-party scripts may have unexpected results or break the site.'),
      '#cols' => '100',
      '#default_value' => $config->get('head_script'),
    ];

    $form['other']['ad_script'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Footer Scripts'),
      '#description' => $this->t('Any code pasted in here will be added before the closing body tag. Please note that third-party scripts may have unexpected results or break the site.'),
      '#cols' => '100',
      '#default_value' => $config->get('ad_script'),
    ];

    $form['other']['mercolocal_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('MercoLocal Affiliate Id'),
      '#size' => 10,
      '#default_value' => $config->get('mercolocal_id'),
    ];

    $form['other']['author'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Default Author'),
      '#size' => 30,
      '#maxlength' => 60,
      '#target_type' => 'user',
    ];

    if ($uid > 0) {
      $form['other']['author']['#default_value'] = $author;
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('etype.adminsettings')
      ->set('etype_e_edition', $form_state->getValue('etype_e_edition'))
      ->set('etype_pub', $form_state->getValue('etype_pub'))
      ->set('etype_ptype', $form_state->getValue('etype_ptype'))
      ->set('etype_author_links_off', $form_state->getValue('etype_author_links_off'))
      ->set('premium_content_message', $form_state->getValue('premium_content_message'))
      ->set('premium_preview', $form_state->getValue('premium_preview'))
      ->set('facebook', $form_state->getValue('facebook'))
      ->set('twitter', $form_state->getValue('twitter'))
      ->set('instagram', $form_state->getValue('instagram'))
      ->set('mercolocal_id', $form_state->getValue('mercolocal_id'))
      ->set('weather_code', $form_state->getValue('weather_code'))
      ->set('ad_script', $form_state->getValue('ad_script'))
      ->set('head_script', $form_state->getValue('head_script'))
      ->set('author', $form_state->getValue('author'))
      ->save();
  }

}
