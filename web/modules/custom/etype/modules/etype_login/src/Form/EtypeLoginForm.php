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

    if (Drupal::currentUser()->isAnonymous()) {

      $form['username'] = [
        '#type' => 'textfield',
        '#title' => $this->t('User Name'),
        '#required' => TRUE,
        '#attributes' => ['tabindex' => 20],
      ];

      $form['password'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Password'),
        '#required' => TRUE,
        '#attributes' => ['tabindex' => 21],
      ];

      $form['#attached']['library'][] = 'etype_login/etype_login';

      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Log In'),
        '#button_type' => 'primary',
      );

    }
    else {

      $name = Drupal::currentUser()->getDisplayName();
      $form['#markup'] = "Hello $name, you are already logged in.";

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
            if (($user = user_load_by_mail($getSubscriberEmailResult)) === FALSE) {
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
                $message = "We can‘t create an account for you on this website because the user name $username already exists in this system. Please email support@etypeservices.com for assistance.";
                Drupal::messenger()->addMessage($message);
              }
            }
            else {
              $account = user_load_by_mail($getSubscriberEmailResult);
              $user = User::load($account->id());
              user_login_finalize($user);
              Drupal::messenger()->addMessage($success_message);
            }
            // Clear cache to reset edition links.
            Drupal::cache('menu')->invalidateAll();
            Drupal::service('plugin.manager.menu.link')->rebuild();
            $redirectDestination = $base_url . $_COOKIE["redirectDestination"];
            $url = Url::fromUri($redirectDestination);
            $form_state->setRedirectUrl($url);
        }

        break;
    }
  }

}
