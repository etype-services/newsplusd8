<?php

namespace Drupal\etype_pico\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use SoapClient;

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
  public $passwd = 'HYHhZ*vz6K7u@ngH';

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
   * EtypePicoEeditionController constructor.
   */
  public function __construct() {
    $this->config = Drupal::config('etype.adminsettings');
    $this->pubId = (int) $this->config->get('etype_pub');
  }

  /**
   * Validate Subscriber in etype.services.
   *
   * @throws \SoapFault
   *
   * @return string
   *    Message from etype.services.
   */
  public function validateSubscriber() {
    $param = [
      'publicationId' => $this->pubId,
      'username' => $this->userName,
      'password' => $this->passwd,
    ];
    $client = new SoapClient($this->webServiceUrl);
    $response = $client->ValidateSubscriber($param);
    $validateSubscriberResult = $response->ValidateSubscriberResult;
    return $validateSubscriberResult->TransactionMessage->Message;
  }

  /**
   * Get Token for access to etype.services.
   *
   * @return string
   *    Returns url with token.
   *
   * @throws \SoapFault
   */
  public function getToken() {
    $client = new SoapClient($this->webServiceUrl);
    $params = [
      'publicationId' => $this->pubId,
      'username' => $this->userName,
    ];
    $data = $client->GenerateUrlForSubscriber($params);
    return $data->GenerateUrlForSubscriberResult;
  }

  /**
   * This is the e-Edition Log in for master Pico user.
   *
   * @throws \SoapFault
   */
  public function goToEedition() {
    if (($result = $this->validateSubscriber()) == 0) {
      $redirect = $this->getToken();
    }
    var_dump($redirect);
    exit;
  }
}