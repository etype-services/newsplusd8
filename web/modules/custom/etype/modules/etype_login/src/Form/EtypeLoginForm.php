<?php

namespace Drupal\etype_login\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use SoapClient;

/**
 * Class EtypeLoginForm.
 *
 * @package Drupal\etype_xml_importer\Form
 */
class EtypeLoginForm extends FormBase {

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
    $client = new soapclient('https://www.etypeservices.com/service_GetPublicationIDByUserName.asmx?WSDL');
    $param = ['UserName' => $form_state->getValue('username')];
    $response = $client->GetPublicationID($param);
    $code = $response->GetPublicationIDResult;
    switch ($code) {
      case "-9":
        $message = "We‘re sorry, either your user name or password is incorrect.";
        Drupal::messenger()->addMessage($message);
        $form_state->setRebuild();
        break;

      case "5239":
        $param['Password'] = $form_state->getValue('password');
        $client = new soapclient('https://www.etypeservices.com/Service_SubscriberLogin.asmx?WSDL');
        $response = $client->ValidateSubscriber($param);
        $param1 = ['UserName' => $form_state->getValue('username')];
        $client1 = new soapclient('https://www.etypeservices.com/Get_EmailbyUserName.asmx?WSDL');
        $response1 = $client1->GetSubscriberEmail($param1);
        //kint($response);
        //kint($response1);
        break;
    }

  }

}
