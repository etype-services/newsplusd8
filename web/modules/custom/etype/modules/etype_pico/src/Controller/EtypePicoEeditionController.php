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
   * Get Token for access to etype.services.
   *
   * @return mixed
   *   Object.
   */
  public function getToken() {
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    $client = new SoapClient('https://publisher.etype.services/webservice.asmx?WSDL');
    $params = [
      'publicationId' => $pubId,
      'username' => 'Pico',
    ];
    $data = $client->GenerateUrlForSubscriber($params);
    return $data->GenerateUrlForSubscriberResult;
  }

  /**
   * Content.
   *
   * @return array
   *   markup
   */
  public function content() {
    return [
      '#title' => 'Read the Paper Online',
      '#theme' => 'e-edition',
    ];
  }

}
