<?php

namespace Drupal\etype_login_dev\Form;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use SoapClient;

/**
 * Class EtypeLoginForm.
 *
 * @package Drupal\etype_login\Form
 */
class EtypeLoginForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * EtypeLoginForm constructor.
   */
  public function __construct() {
    $this->config = $config = Drupal::config('etype.adminsettings');

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'etype_login_form';
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

      $form['password'] = [
        '#type' => 'password',
        '#title' => $this->t('Password'),
        '#required' => TRUE,
      ];

      $form['help'] = [
        '#type' => 'item',
        '#markup' => t('<a href="/etype-forgot-password">I forgot my password.</a>'),
      ];

      $form['#attached']['library'][] = 'etype_login/etype_login';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Log In'),
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
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \SoapFault
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    global $base_url;

    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');

    $pubId = $this->config->get('etype_pub');
    $pubId = 2;
    $message = "We‘re sorry, either your user name or password is incorrect.";
    $success_message = "Hello $username, you are now logged in!";

    $client = new soapclient('http://etype.wecode4u.com/webservice.asmx?WSDL');
    $param = ['publicationId' => $pubId];
    $param['username'] = $username;
    $param['password'] = $password;
    $response = $client->ValidateSubscriber($param);
    $validateSubscriberResult = $response->ValidateSubscriberResult;
    $responseCode = $validateSubscriberResult->TransactionMessage->Message;

    switch ($responseCode) {
      case "-1":
        Drupal::messenger()->addMessage($message);
        break;

      case "-2":
        $message1 = "It looks like your subscription has expired. <a href='https://www.etypeservices.com/Subscriber/SignIn.aspx?ReturnUrl=https://www.etypeservices.com/Subscriber/ReSubscribe.aspx?PubID=$pubId'>Re-subscribe now!</a> .";
        Drupal::messenger()->addMessage($message1);
        break;

      default:
        $subscriberEmail = $validateSubscriberResult->Email;
        if (($user = user_load_by_mail($subscriberEmail)) === FALSE) {
          $check = user_load_by_name($username);
          if ($check == FALSE) {
            $user = User::create();
            $user->setPassword($password);
            $user->enforceIsNew();
            $user->setEmail($subscriberEmail);
            $user->setUsername($username);
            $user->activate();
            $user->save();
            user_login_finalize($user);
            Drupal::messenger()->addMessage($success_message);
          }
          else {
            $message = "We can‘t create an account for you on this website because the user name $username already exists in this system. Please email support@etypeservices.com for assistance.";
            Drupal::messenger()->addMessage($message);
          }
        }
        else {
          $account = user_load_by_mail($subscriberEmail);
          $user = User::load($account->id());
          user_login_finalize($user);
          Drupal::messenger()->addMessage($success_message);
        }
        // Clear cache to reset e-Edition links.
        Drupal::cache('menu')->invalidateAll();
        Drupal::service('plugin.manager.menu.link')->rebuild();
        $redirectDestination = $base_url;
        if (isset($_COOKIE["redirectDestination"])) {
          $redirectDestination .= $_COOKIE["redirectDestination"];
        }
        $url = Url::fromUri($redirectDestination);
        $form_state->setRedirectUrl($url);
    }
  }

}
