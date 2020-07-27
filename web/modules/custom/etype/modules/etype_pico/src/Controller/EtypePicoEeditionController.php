<?php

namespace Drupal\etype_pico\Controller;

use Drupal;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use SoapClient;

/**
 * Class EtypePicoEeditionController.
 *
 * @package Drupal\etype_pico\Controller
 */
class EtypePicoEeditionController extends ControllerBase {

  /**
   * Returns a verified url for the subscriber with an access token.
   *
   * This is used to open the etypeservices page for the paper.
   *
   * @return string
   *   The url with the token.
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
   * Returns a TrustedRedirectResponse.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   Redirect to external uri.
   */
  public function gotoEedition() {
    exit;
    $url = $this->getToken();
    $response = new TrustedRedirectResponse($url);
    /* We do not want the response cached */
    $cacheable_metadata = new CacheableMetadata();
    $cacheable_metadata->setCacheMaxAge(0);
    $response->addCacheableDependency($cacheable_metadata);
    return $response;
  }

}
