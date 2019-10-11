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
    $message = new TranslatableMarkup('We were unable to retrieve any details for this account. Please contact us.');
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
    $message = new TranslatableMarkup('We were unable to update your account details Please try later.');
    parent::__construct($message);
  }

}

/**
 * Class EtypePasswordException.
 *
 * @package Drupal\etype_login\Form
 */
class EtypePasswordException extends Exception {

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
    $message = new TranslatableMarkup('Your current password does not match our records. Please try again.');
    parent::__construct($message);
  }

}

/**
 * Class EtypePasswordChangeException.
 *
 * @package Drupal\etype_login\Form
 */
class EtypePasswordChangeException extends Exception {

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
    $message = new TranslatableMarkup('Sorry, we were not able to update your password. Please try again later.');
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

      try {
        if (!isset($response->GetDetailsByUserNameResult->UserDetails)) {
          throw new EtypeLoginException();
        }
      }
      catch (EtypeLoginException $e) {
        $this->messenger->addError($e->getMessage());
        return ['#markup' => ''];
      }

      $details = $response->GetDetailsByUserNameResult->UserDetails;

      $form['sid'] = [
        '#type' => 'hidden',
        '#default_value' => $details->ID,
      ];

      /*$string = t("Name");
      $string .= ': ' . $details->UserName;
      $form['name'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];*/

      $string = t("Email");
      $string .= ': ' . $details->Email;
      $form['email'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $string = t("Submit the form to update any information below:");
      $form['help'] = [
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

      $string = t("Fill out the next three fields to change your password:");
      $form['password_help'] = [
        '#type' => 'item',
        '#markup' => $string,
      ];

      $form['oldPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Current password'),
      ];

      $form['newPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('New password'),
      ];

      $form['confirmPassword'] = [
        '#type' => 'password',
        '#title' => $this->t('Confirm your new password'),
      ];

      $form['#attached']['library'][] = 'etype_login/etype_login';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Update my information'),
        '#button_type' => 'primary',
      ];

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

    $message = "Your account was updated successfully.";

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
    $oldPassword = $form_state->getValue('oldPassword');
    $newPassword = $form_state->getValue('newPassword');
    $confirmPassword = $form_state->getValue('confirmPassword');
    if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {
      $user_name = Drupal::currentUser()->getAccountName();
      $param = ['UserName' => $user_name];
      $client = new soapclient('https://www.etypeservices.com/service_GetPasswordByUserName.asmx?WSDL');
      $response = $client->GetPasswordByUserName($param);
      try {
        if ($oldPassword !== $response->GetPasswordByUserNameResult) {
          throw new EtypePasswordException();
        }
      }
      catch (EtypePasswordException $e) {
        $this->messenger->addError($e->getMessage());
        return ['#markup' => ''];
      }

      $param = ['UserName' => $user_name, 'Password' => $newPassword];
      $client = new soapclient('https://www.etypeservices.com/Service_ChangePassword.asmx?WSDL');
      $response = $client->ChangePassword($param);
      try {
        if ($response->ChangePasswordResult !== 0) {
          throw new EtypePasswordChangeException();
        }
      }
      catch (EtypePasswordChangeException $e) {
        $this->messenger->addError($e->getMessage());
        return ['#markup' => ''];
      }

      $message .= " Your password was also updated.";

    }

    $this->messenger->addStatus($message);

  }

}
