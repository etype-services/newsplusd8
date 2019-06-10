<?php

namespace Drupal\etype_login\Form;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use SoapClient;

/**
 * Class EtypeMyAccountForm.
 *
 * @package Drupal\etype_login\Form
 */
class EtypeMyAccountForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * EtypeMyAccountForm constructor.
   */
  public function __construct() {
    $this->config = $config = Drupal::config('etype.adminsettings');

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_my_account_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if (Drupal::currentUser()->isAnonymous()) {

      $form['#markup'] = "Please log in to access this page.";

    }
    else {

      $user_name = Drupal::currentUser()->getAccountName();
      $param = ['UserName' => $user_name];

      $client = new soapclient('https://www.etypeservices.com/Service_GetDetails_ByUserName.asmx?WSDL');
      $response = $client->GetDetailsByUserName($param);
      $details = $response->GetDetailsByUserNameResult->UserDetails;
      var_dump($details);

      $form['sid'] = [
        '#type' => 'hidden',
        '#default_value' => $details->ID,
      ];

      $string = t("Name");
      $string .= ': ' . $details->UserName;
      $form['name'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $string = t("Email");
      $string .= ': ' . $details->Email;
      $form['email'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $form['firstName'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First name'),
        '#required' => TRUE,
        '#default_value' => $details->FirstName,
      ];

      $form['lastName'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last name'),
        '#required' => TRUE,
        '#default_value' => $details->LastName,
      ];

      $form['streetAddress'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Street address'),
        '#required' => TRUE,
        '#default_value' => $details->Address,
      ];

      $form['city'] = [
        '#type' => 'textfield',
        '#title' => $this->t('City'),
        '#required' => TRUE,
        '#default_value' => $details->City,
      ];

      $form['state'] = [
        '#type' => 'textfield',
        '#title' => $this->t('State'),
        '#required' => TRUE,
        '#default_value' => $details->State,
      ];

      $form['zip'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Zip Code'),
        '#required' => TRUE,
        '#default_value' => $details->Zip,
      ];

      $form['phone'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Phone'),
        '#default_value' => $details->Phone,
      ];

      $string = t("Fill out the following fields to change your password:");
      $form['help'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $form['oldPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Old password'),
        '#required' => TRUE,
      ];

      $form['newPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('New password'),
        '#required' => TRUE,
      ];

      $form['confirmPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Confirm your new password'),
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

  }

}
