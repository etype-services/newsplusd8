<?php

namespace Drupal\etype_login_v2\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use SoapClient;

/**
 * Class EtypeV2VerifyAccountController.
 *
 * @package Drupal\etype_login_v2\Controller
 */
class EtypeV2VerifyAccountController extends ControllerBase {

  /**
   * Returns SOAP response.
   *
   * A *logged in* url for the subscriber.
   * This is used to open the etypeservices page for the paper.
   *
   * @param string $username
   *   The user name.
   *
   * @return string
   *   The url with the token.
   */
  public function getToken($username = NULL) {
    if (empty($username)) {
      $username = Drupal::currentUser()->getAccountName();
    }
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    $client = new soapclient('https://publisher.etype.services/webservice.asmx?WSDL');
    $params = [
      'publicationId' => 65,
      'username' => 'tgolden',
    ];
    $data = $client->GenerateUrlForSubscriber($params);
    echo $data->GenerateUrlForSubscriberResult;
    exit;
    return $data->GenerateUrlForSubscriberResult;
  }
}