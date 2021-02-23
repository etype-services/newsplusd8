<?php

namespace Drupal\etype_pico\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use SoapClient;
use SoapFault;

/**
 * Class EtypePicoEeditionController.
 *
 * @package Drupal\etype_pico\Controller
 */
class EtypePicoEeditionController extends ControllerBase {

  /**
   * Publication Id.
   *
   * @var int
   */
  public $pubId = '';

  /**
   * User Name.
   *
   * @var string
   */
  public $userName = 'Pico';

  /**
   * Password.
   *
   * @var string
   */
  public $passwd = '';

  /**
   * Web Service URL.
   *
   * @var string
   */
  public $webServiceUrl = 'https://publisher.etype.services/webservice.asmx?WSDL';

  /**
   * Configuration Settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  public $config;

  /**
   * Configuration Settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  public $picoConfig;

  /**
   * EtypePicoEeditionController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype.adminsettings');
    $this->picoConfig = Drupal::config('etype_pico.settings');
    $this->pubId = (int) $this->config->get('etype_pub');
    $this->passwd = $this->picoConfig->get('picoPassword');
  }

  /**
   * Validate Subscriber in etype.services.
   *
   * @return string
   *   Message from etype.services.
   */
  public function validateSubscriber(): ?string {
    $param = [
      'publicationId' => $this->pubId,
      'username' => $this->userName,
      'password' => $this->passwd,
    ];
    try {
      $client = new SoapClient($this->webServiceUrl);
      $response = $client->ValidateSubscriber($param);
      $validateSubscriberResult = $response->ValidateSubscriberResult;
      return $validateSubscriberResult->TransactionMessage->Message;
    }
    catch (SoapFault $exception) {
      $message = 'Could not connect to SoapClient.';
      Drupal::logger('my_module')->error($message);
      return NULL;
    }
  }

  /**
   * Get Url with Token for access to etype.services.
   *
   * @return string|null
   *   Returns url with token.
   */
  public function getToken(): ?string {
    $params = [
      'publicationId' => $this->pubId,
      'username' => $this->userName,
    ];
    try {
      $client = new SoapClient($this->webServiceUrl);
      $data = $client->GenerateUrlForSubscriber($params);
      return $data->GenerateUrlForSubscriberResult;
    }
    catch (SoapFault $exception) {
      $message = 'Could not connect to SoapClient.';
      Drupal::logger('my_module')->error($message);
      return NULL;
    }
  }

  /**
   * This gets an authenticated e-Edition url.
   */
  public function getEeditionUrl(): ?string {
    $response = NULL;
    if (($result = $this->validateSubscriber()) == 0) {
      $response = $this->getToken();
    }
    return $response;
  }

  /**
   * Content.
   *
   * @return array
   *   markup
   */
  public function content(): array {
    $markup = '
<div style="padding:5vh;text-align:center;" class="PicoSignal" id="PicoBlock">
    <div>One moment, we are redirecting you.</div>
</div>
';
    return [
      '#title' => '',
      '#children' => Markup::create($markup),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
