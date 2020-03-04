<?php

namespace Drupal\etype_login\Form;

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

    $pubId = (int) $this->config->get('etype_pub');
    kint($pubId);

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

    $pubId = (int) $this->config->get('etype_pub');
    $message = "We‘re sorry, either your user name or password is incorrect.";
    $success_message = "Hello $username, you are now logged in!";

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
        $param1 = $param;
        $param['Password'] = $password;
        $client = new soapclient('https://www.etypeservices.com/Service_SubscriberLogin.asmx?WSDL');
        $response = $client->ValidateSubscriber($param);
        $client1 = new soapclient('https://www.etypeservices.com/Get_EmailbyUserName.asmx?WSDL');
        $response1 = $client1->GetSubscriberEmail($param1);
        $validateSubscriberResult = $response->ValidateSubscriberResult;
        $getSubscriberEmailResult = $response1->GetSubscriberEmailResult;

        switch ($validateSubscriberResult) {
          case "-1":
            $message1 = "It looks like your subscription has expired. <a href='https://www.etypeservices.com/Subscriber/SignIn.aspx?ReturnUrl=https://www.etypeservices.com/Subscriber/ReSubscribe.aspx?PubID=$pubId'>Re-subscribe now!</a> .";
            Drupal::messenger()->addMessage($message1);
            break;

          case "-5":
            Drupal::messenger()->addMessage($message);
            break;

          default:
            $check = user_load_by_name($username);
            if ($check == FALSE) {
              $user = User::create();
              $user->setPassword($password);
              $user->enforceIsNew();
              $user->setEmail($getSubscriberEmailResult);
              $user->setUsername($username);
              $user->activate();
              $user->save();
              user_login_finalize($user);
              Drupal::messenger()->addMessage($success_message);
            }
            else {
              $user = User::load($check->id());
              $user->setEmail($getSubscriberEmailResult);
              $user->save();
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

        break;

      default:
        $message = "We‘re sorry, something unexpected happened.";
        Drupal::messenger()->addMessage($message);

    }
  }

}
