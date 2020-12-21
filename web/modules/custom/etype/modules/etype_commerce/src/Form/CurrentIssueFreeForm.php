<?php

namespace Drupal\etype_commerce\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

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

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your email address'),
      '#required' => TRUE,
      '#default_value' => $form_state->getValue('email'),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Choose a User Name'),
      '#required' => TRUE,
      '#default_value' => $form_state->getValue('username'),
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Set a Password'),
      '#required' => TRUE,
      '#description' => "At least 8 characters",
    ];

    $form['confirmPassword'] = [
      '#type' => 'password',
      '#title' => $this->t('Confirm Your Password'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    ];

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $confirmPassword = $form_state->getValue('confirmPassword');
    $email = $form_state->getValue('email');

    if (($user = user_load_by_mail($username)) !== FALSE) {
      $form_state->setErrorByName('username', $this->t('We can‘t create an account for you on this website because that user name already exists in our system. Please <a href="/user/login">login</a> or choose a different user name.'));
    }
    elseif (($user = user_load_by_name($email)) !== FALSE) {
      $form_state->setErrorByName('email', $this->t('We can‘t create an account for you on this website because the email address supplied already exists in our system. Please <a href="/user/login">login</a> or use a different email address.'));
    }
    elseif (strlen($password) < 8) {
      $form_state->setErrorByName('password', $this->t('Please select a password of at least 8 characters.'));
    }
    elseif (strcmp($password, $confirmPassword) !== 0) {
      $form_state->setErrorByName('confirmPassword', $this->t('The passwords entered do not match. Please re-enter your preferred password.'));
    }
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
    // Remember entered values.
    $form_state->setRebuild();

    $email = $form_state->getValue('email');
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    // $url = Url::fromUri("<front>");
    // $form_state->setRedirectUrl($url);
  }

}
