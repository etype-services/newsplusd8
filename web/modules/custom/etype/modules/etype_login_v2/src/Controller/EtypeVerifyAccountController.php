<?php

namespace Drupal\etype_login_v2\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use SoapClient;

/**
 * Class EtypeVerifyAccountController.
 *
 * @package Drupal\etype_login_v2\Controller
 */
class EtypeVerifyAccountController extends ControllerBase {

  /**
   * Returns SOAP response.
   */
  public function getToken() {

    $username = Drupal::currentUser()->getAccountName();
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    $client = new soapclient('https://publisher.etype.services/webservice.asmx?WSDL');
    $params = [
      'publicationId' => $pubId,
      'username' => $username,
    ];
    $response = $client->GenerateUrlForSubscriber($params);
    echo $response;

  }

}
