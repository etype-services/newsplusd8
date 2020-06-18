<?php

namespace Drupal\etype_login_v2\Controller;

use Drupal;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Class EtypeV2EeditionController.
 *
 * @package Drupal\etype_login_v2\Controller
 */
class EtypeV2EeditionController extends ControllerBase {

  /**
   * Returns a renderable array.
   *
   * @param int $pubId
   *   the Id of the publication.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   Redirect to external uri.
   */
  public function gotoEedition($pubId = NULL) {
    $username = 'invalid';
    $logged_in = Drupal::currentUser()->isAuthenticated();
    if ($logged_in > 0) {
      $username = Drupal::currentUser()->getAccountName();
    }
    $url = (new EtypeV2VerifyAccountController)->getToken($username);
    $response = new TrustedRedirectResponse($url);
    $cacheable_metadata = new CacheableMetadata();
    $cacheable_metadata->setCacheMaxAge(0);
    $response->addCacheableDependency($cacheable_metadata);
    return $response;
  }

}
