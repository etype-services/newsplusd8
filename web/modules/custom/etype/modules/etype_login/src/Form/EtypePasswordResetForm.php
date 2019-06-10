<?php

namespace Drupal\etype_login\Form;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use SoapClient;

/**
 * Class EtypePasswordResetForm.
 *
 * @package Drupal\etype_login\Form
 */
class EtypePasswordResetForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * EtypePasswordResetForm constructor.
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

      $form['#attached']['library'][] = 'etype_login/etype_login';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Send me my password!'),
        '#button_type' => 'primary',
      );

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

    $pubId = $this->config->get('etype_pub');
    $message = "We‘re sorry, we can‘t find an account for that user name at this publication.";
    $success_message = "Your password has been sent to your email adddress.";

    $client = new soapclient('https://www.etypeservices.com/service_GetPublicationIDByUserName.asmx?WSDL');
    $param = ['UserName' => $username];
    $response = $client->GetPublicationID($param);
    $code = $response->GetPublicationIDResult;
    switch ($code) {
      case "-9":
        Drupal::messenger()->addMessage($message);
        $form_state->setRebuild();
        break;

      case $pubId:
        $client = new soapclient('https://www.etypeservices.com/service_ForgetPassword.asmx?WSDL');
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

        break;
    }
  }

}
