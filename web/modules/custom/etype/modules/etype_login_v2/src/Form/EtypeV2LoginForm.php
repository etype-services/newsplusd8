<?php

namespace Drupal\etype_login_v2\Form;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use SoapClient;

/**
 * Class EtypeV2LoginForm.
 *
 * @package Drupal\etype_login_v2\Form
 */
class EtypeV2LoginForm extends FormBase {

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

      $form['help'] = [
        '#type' => 'item',
        '#markup' => t('<a href="/etype-forgot-password">I forgot my password.</a>'),
      ];

      $form['#attached']['library'][] = 'etype_login_v2/etype_login_v2';

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
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    global $base_url;

    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $pubId = $form_state->getValue('pubId');

    $success_message = "Hello $username, you are now logged in!";

    $client = new soapclient('https://publisher.etype.services/webservice.asmx?WSDL');
    $param = [
      'publicationId' => $pubId,
      'username' => $username,
      'password' => $password,
    ];
    $response = $client->ValidateSubscriber($param);
    $validateSubscriberResult = $response->ValidateSubscriberResult;
    $responseCode = $validateSubscriberResult->TransactionMessage->Message;

    switch ($responseCode) {
      case '0':
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
        break;

      case "-2":
        Drupal::messenger()->addMessage("Your subscription has expired.");
        break;

      default:
        Drupal::messenger()->addMessage("We‘re sorry, either your user name or password is incorrect!");
        break;
    }
  }

}
