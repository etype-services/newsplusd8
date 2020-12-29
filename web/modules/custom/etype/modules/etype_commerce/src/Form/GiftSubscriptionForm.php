<?php

namespace Drupal\etype_commerce\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use CommerceGuys\Addressing\AddressFormat\AddressField;
use CommerceGuys\Addressing\AddressFormat\FieldOverride;

/**
 * Class GiftSubscriptionForm provides access to free issue.
 *
 * @package Drupal\etype_commerce\Form
 */
class GiftSubscriptionForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The order id.
   *
   * @var int
   */
  private $orderId;

  /**
   * GiftSubscriptionForm constructor.
   */
  public function __construct() {
    $this->config = $config = \Drupal::config('etype_commerce.adminsettings');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'gift_subscription_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $orderId = NULL): array {

    $subEmail = '';
    $printSub = '';

    $query = \Drupal::entityQuery('gift_subscription')
      ->condition('order_id', $orderId);
    $ids = $query->execute();
    if (count($ids) == 0) {
      $form['text'] = [
        "#markup" => "This gift subscription has already been redeemed or is invalid.",
      ];
    }
    else {
      try {
        $entity = \Drupal::entityTypeManager()
          ->getStorage('gift_subscription')
          ->load(reset($ids));
        $email = $entity->get('email')->getValue();
        $subEmail = $email[0]['value'];
        $print = $entity->get('print')->getValue();
        $printSub = $print[0]['value'];
      }
      catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
      }
      $check = user_load_by_mail($subEmail);
      if ($check !== FALSE) {
        $form['text'] = [
          "#markup" => "There is already an account for <strong>$subEmail</strong>. Please <a href=\"/user/login\">login here</a>.",
        ];
      }
      else {
        $form['text'] = [
          "#markup" => "This gift subcription is for <strong>" . $subEmail . "</strong>. Please fill out this form then look for a confirmation email with a link to complete your registration.",
        ];

        $form['username'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Choose a User Name'),
          '#required' => TRUE,
        ];

        $form['password'] = [
          '#type' => 'password',
          '#title' => $this->t('Set a Password'),
          '#required' => TRUE,
          '#description' => "At least 8 characters",
        ];

        $form['confirmPassword'] = [
          '#type' => 'password',
          '#title' => $this->t('Confirm Your Password'),
          '#required' => TRUE,
        ];

        // @todo better way to check for Print Subscription.
        if ($printSub == 7) {
          $form['addressText'] = [
            "#markup" => "Your gift subscription includes a printed copy of the paper. Please enter the address where you wish to receive this.",
          ];

          $form['address'] = [
            '#type' => 'address',
            '#title' => t('Delivery Address'),
            '#used_fields' => [
              'givenName',
              'familyName',
              'addressLine1',
              'addressLine2',
              'locality',
              'postalCode',
              'administrativeArea',
            ],
            '#field_overrides' => [
              AddressField::ORGANIZATION => FieldOverride::REQUIRED,
            ],
            '#available_countries' => ['US'],
          ];
        }

        $form['actions']['#type'] = 'actions';

        $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Register'),
          '#button_type' => 'primary',
        ];
      }
    }

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $confirmPassword = $form_state->getValue('confirmPassword');
    $email = $form_state->getValue('email');

    if (($user = user_load_by_name($username)) !== FALSE) {
      $form_state->setErrorByName('username', $this->t('We can‘t create an account for you on this website because that user name already exists in our system. Please <a href="/user/login">login</a> or choose a different user name.'));
    }
    elseif (($user = user_load_by_name($email)) !== FALSE) {
      $form_state->setErrorByName('email', $this->t('We can‘t create an account for you on this website because the email address supplied already exists in our system. Please <a href="/user/login">login</a> or use a different email address.'));
    }
    elseif (strlen($password) < 8) {
      $form_state->setErrorByName('password', $this->t('Please select a password of at least 8 characters.'));
    }
    elseif (strcmp($password, $confirmPassword) !== 0) {
      $form_state->setErrorByName('confirmPassword', $this->t('The passwords entered do not match. Please re-enter your preferred password.'));
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
   * @throws \Drupal\Core\Entity\EntityStorageException
   *   Throw Exception.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Create new user.
    $email = $form_state->getValue('email');
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $address = $form_state->getValue('address');
    $user = User::create();
    $user->setPassword($password);
    $user->enforceIsNew();
    $user->setEmail($email);
    $user->setUsername($username);
    $user->set('field_address', $address);
    $user->activate();
    $user->save();
    \Drupal::messenger()->addMessage("Hello $username! We’ve created your account. Look for an email with instructions to confirm your subscription.");
    $url = Url::fromRoute('<front>');
    $form_state->setRedirectUrl($url);
  }

}
