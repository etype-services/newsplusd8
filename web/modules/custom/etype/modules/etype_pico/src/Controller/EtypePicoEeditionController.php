<?php

namespace Drupal\etype_pico\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use SoapClient;
use SoapFault;

/**
 * Class EtypePicoEeditionController.
 *
 * @package Drupal\etype_pico\Controller
 */
class EtypePicoEeditionController extends ControllerBase {
  /**
   * eType Version.
   *
   * @var string
   */
  public $etypeVersion;

  /**
   * Publication Id.
   *
   * @var int
   */
  public $pubId;

  /**
   * User Name.
   *
   * @var string
   */
  public $userName;

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
    $this->userName = $this->picoConfig->get('picoUser');
    $this->etypeVersion = $this->picoConfig->get('etypeVersion');
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
      Drupal::logger('pico')->error($message);
      return NULL;
    }
  }

  /**
   * This gets an authenticated e-Edition url.
   */
  public function getEeditionUrl(): ?string {
    /* V1 */
    if ($this->etypeVersion == '1' ) {
      /* There's no point in checking anything, no token required */
      $e_editions = etype_e_editions();
      $response = $e_editions[0]['path'];
    }
    else {
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
    $config = Drupal::config('etype_pico.settings');
    $picoLandingPage = $config->get('picoLandingPage');
    $url = (new EtypePicoEeditionController)->getEeditionUrl();
    //Drupal::logger('pico')->info($url);
    return [
      '#title' => '',
      '#theme' => 'e_edition',
      '#attached' => [
        'library' =>
          [
            'etype_pico/pico-auth',
          ],
        'drupalSettings' =>
          [
            'toknizdUrl' => $url,
            'picoLandingPage' => $picoLandingPage,
          ],
        ],
    ];
  }

}
