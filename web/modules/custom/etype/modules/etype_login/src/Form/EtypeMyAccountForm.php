<?php

namespace Drupal\etype_login\Form;

use Drupal;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception;
use SoapClient;

/**
 * Class EtypeLoginException.
 *
 * @package Drupal\etype_login\Form
 */
class EtypeLoginException extends Exception {

  /**
   * EtypeLoginException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('We were unable to retrieve your account details. Please try later.');
    parent::__construct($message);
  }

}

/**
 * Class EtypeUpdateException.
 *
 * @package Drupal\etype_login\Form
 */
class EtypeUpdateException extends Exception {

  /**
   * Var Setup.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * EtypeUpdateException constructor.
   */
  public function __construct() {
    $message = new TranslatableMarkup('We were unable to updae your account details Please try later.');
    parent::__construct($message);
  }

}

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
    $this->messenger = Drupal::messenger();

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

      /* throw Exception and return empty page with message if the wire database setings are missing */
      try {
        $details = $response->GetDetailsByUserNameResult->UserDetails;
        if (empty($details->ID)) {
          throw new EtypeLoginException();
        }
      }
      catch (EtypeLoginException $e) {
        $this->messenger->addError($e->getMessage());
        return ['#markup' => ''];
      }

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

      $form['address'] = [
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

      /*$string = t("Fill out the following fields to change your password:");
      $form['help'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $form['oldPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Old password'),
      ];

      $form['password']['newPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('New password'),
      ];

      $form['password']['confirmPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Confirm your new password'),
      ];*/

      $form['#attached']['library'][] = 'etype_login/etype_login';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Update my information'),
        '#button_type' => 'primary',
      );

    }

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $val = $form_state->getValue('newPassword');
    $valTwo = $form_state->getValue('confirmPassword');
    if ($val !== $valTwo) {
      $form_state->setErrorByName('password', $this->t('The new passwords do not match.'));
    }
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
   *
   * @return array
   *   Markup
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Account Details.
    $param = [
      'FirstName' => $form_state->getValue('firstName'),
      'LastName' => $form_state->getValue('lastName'),
      'StreetAddress' => $form_state->getValue('address'),
      'City' => $form_state->getValue('city'),
      'State' => $form_state->getValue('state'),
      'PostalCode' => $form_state->getValue('zip'),
      'Phone' => $form_state->getValue('phone'),
      'SubscriberID' => $form_state->getValue('sid'),
    ];

    $client = new soapclient('https://www.etypeservices.com/Service_EditSubscriberProfile.asmx?wsdl');

    try {
      $response = $client->SubscriberUpdateProfile($param);
      if (is_null($response)) {
        throw new EtypeUpdateException();
      }
    }
    catch (EtypeUpdateException $e) {
      $this->messenger->addError($e->getMessage());
      return ['#markup' => ''];
    }

    // Password Update.
    $param = [];

    $this->messenger->addStatus("Your account was updated successfully.");

  }

}
