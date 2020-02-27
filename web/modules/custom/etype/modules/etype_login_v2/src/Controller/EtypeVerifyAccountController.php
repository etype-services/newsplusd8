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
   *
   * A *logged in* url for the subscriber.
   * This is used to open the etypeservices page for the paper.
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
    $data = $client->GenerateUrlForSubscriber($params);
    return new Response(
      $data->GenerateUrlForSubscriberResult,
      Response::HTTP_OK,
      ['content-type' => 'text/plain']
    );

  }

}
