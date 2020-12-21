<?php

namespace Drupal\etype_commerce\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CurrentIssueFreeForm provides access to free issue.
 *
 * @package Drupal\etype_commerce\Form
 */
class CurrentIssueFreeForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * CurrentIssueFreeForm constructor.
   */
  public function __construct() {
    $this->config = $config = \Drupal::config('etype_commerce.adminsettings');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'current_issue_free_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    if (\Drupal::currentUser()->isAnonymous()) {

      $form['username'] = [
        '#type' => 'textfield',
        '#title' => $this->t('User Name'),
        '#required' => TRUE,
      ];

      $form['password'] = [
        '#type' => 'password',
        '#title' => $this->t('Password'),
        '#required' => TRUE,
      ];

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Log In'),
        '#button_type' => 'primary',
      ];

    }
    else {

      $name = \Drupal::currentUser()->getDisplayName();
      $string = t("Hello");
      $string .= ' ' . $name . ', ';
      $string .= t("you are already logged in");
      $form['help'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

    }

    return $form;

  }

  /**
   * Submit handler.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Nothing yet.
  }

}
