<?php

namespace Drupal\etype_login_v2\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use SoapClient;
use Symfony\Component\HttpFoundation\Response;

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
      'publicationId' => 1,
      'username' => 'alind',
    ];
    $response = $client->GenerateUrlForSubscriber($params);
    var_dump($response);
    return $response->GenerateUrlForSubscriber;

  }

}
