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
   * Returns a verified url for the subscriber with an access token.
   *
   * This is used to open the etypeservices page for the paper.
   *
   * @param string $username
   *   The user name.
   * @param int|null $pubId
   *   the Id of the publication.
   *
   * @return string
   *   The url with the token.
   */
  public function getToken($username = NULL, $pubId = NULL): string {
    if (empty($username)) {
      $username = Drupal::currentUser()->getAccountName();
    }
    $client = new soapclient('https://publisher.etype.services/webservice.asmx?WSDL');
    $params = [
      'publicationId' => $pubId,
      'username' => $username,
    ];
    $data = $client->GenerateUrlForSubscriber($params);
    return $data->GenerateUrlForSubscriberResult;
  }
}
