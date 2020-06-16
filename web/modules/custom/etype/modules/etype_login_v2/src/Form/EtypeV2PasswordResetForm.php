<?php

namespace Drupal\etype_login_v2\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use SoapClient;

/**
 * Class EtypeV2PasswordResetForm.
 *
 * @package Drupal\etype_login\Form
 */
class EtypeV2PasswordResetForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * EtypeV2PasswordResetForm constructor.
   */
  public function __construct() {
    $this->config = $config = Drupal::config('etype.adminsettings');

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_password_reset_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if (Drupal::currentUser()->isAnonymous()) {

      $form['username'] = [
        '#type' => 'textfield',
        '#title' => $this->t('User Name'),
        '#required' => TRUE,
      ];

      $e_editions = etype_e_editions();
      if (count($e_editions) > 1) {
        $options = [];
        foreach ($e_editions as $edition) {
          $options[$edition['pubId']] = $edition['site_name'];
        }
        $form['pubId'] = [
          '#title' => $this->t('Choose your publication'),
          '#type' => 'select',
          '#options' => $options,
          // Add Bulma classes.
          '#attributes' => ['class' => ['select', 'is-fullwidth']],
          '#required' => TRUE,
        ];
      }
      else {
        $form['pubId'] = [
          '#type' => 'hidden',
          '#default_value' => $e_editions[0]['pubId'],
        ];
      }

      $form['#attached']['library'][] = 'etype_login_v2/etype_login_v2';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send me my password'),
        '#button_type' => 'primary',
      ];

    }
    else {

      $name = Drupal::currentUser()->getDisplayName();
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
   *
   * @throws \SoapFault
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $username = $form_state->getValue('username');
    $pubId = $form_state->getValue('pubId');

    $message = "We‘re sorry, we can‘t find an account for that user name at this publication.";
    $success_message = "Your password has been sent to your email address.";

    $client = new soapclient('https://publisher.etype.services/webservice.asmx?WSDL');
    $param = ['publicationId' => $pubId];
    $param['username'] = $username;
    $response = $client->ForgetPassword($param);
    $code = $response->ForgetPasswordResult;

    switch ($code) {
      case "-1":
        Drupal::messenger()->addMessage($message);
        break;

      default:
        Drupal::messenger()->addMessage($success_message);
        break;
    }
  }

}
