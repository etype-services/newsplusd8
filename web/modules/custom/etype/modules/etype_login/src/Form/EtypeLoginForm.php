<?php

namespace Drupal\etype_login\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Session\SessionManagerInterface;
use SoapClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EtypeLoginForm.
 *
 * @package Drupal\etype_xml_importer\Form
 */
class EtypeLoginForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The session manager service.
   *
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  protected $sessionManager;

  /**
   * EtypeLoginForm constructor.
   *
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   *   The session manager service.
   */
  public function __construct(SessionManagerInterface $session_manager) {
    $this->config = $config = Drupal::config('etype.adminsettings');
    $this->sessionManager = $session_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('session_manager')
    );
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

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Name'),
      '#required' => TRUE,
      '#attributes' => ['tabindex' => 20],
      '#default_value' => 'Jamculp',
    ];

    $form['password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
      '#attributes' => ['tabindex' => 21],
      '#default_value' => 'Bruno1941',
    ];

    $form['#attached']['library'][] = 'etype_login/etype_login';

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Log In'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $validateSubscriberResult = 0;
    $getSubscriberEmailResult = '';
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');

    $pubId = $this->config->get('etype_pub');
    $message = "We‘re sorry, either your user name or password is incorrect.";

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
        Drupal::messenger()->addMessage($validateSubscriberResult);
        Drupal::messenger()->addMessage($getSubscriberEmailResult);
        break;
    }

    switch ($validateSubscriberResult) {
      case "-1":
        $message1 = "It looks like your subscription has expired. <a href='https://www.etypeservices.com/Subscriber/SignIn.aspx?ReturnUrl=https://www.etypeservices.com/Subscriber/ReSubscribe.aspx?PubID=$pubId'>Re-subscribe now!</a> .";
        Drupal::messenger()->addMessage($message1);
        break;

      case "-5":
        Drupal::messenger()->addMessage($message);
        break;

      default:
        if ($user = user_load_by_mail($getSubscriberEmailResult) == FALSE) {
          $check = user_load_by_name($username);

          if ($check == FALSE) {
            $user = User::create();
            $user->setPassword($password);
            $user->enforceIsNew();
            $user->setEmail($getSubscriberEmailResult);
            $user->setUsername($username);
            $user->save();
            $newuser = User::load($user->id());
            user_login_finalize($newuser);
          }
          else {
            $message = "We can‘t create an account for you on this website because the user name $username already exists in this system. Please email <a href=\"mailto:support@etypeservices.com\">support@etypeservices.com</a> for assistance.";
            Drupal::messenger()->addMessage($message);
          }
        }

    }
  }

}
