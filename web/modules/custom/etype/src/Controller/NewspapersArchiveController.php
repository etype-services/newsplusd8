<?php

namespace Drupal\etype\Controller;

use Drupal;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Class NewspapersArchiveController.
 *
 * @package Drupal\etype\Controller
 */
class NewspapersArchiveController extends ControllerBase {
  /**
   * Newspapers.com key.
   *
   * @var int
   */
  public $key;

  /**
   * Newspapers.com link.
   *
   * @var int
   */
  public $link;

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
    $this->key = $this->config->get('newspapers_archive');
    $this->link = $this->config->get('newspapers_archive_link');
  }

  /**
   * @param $time
   * @param $key
   * @return string
   */
  protected function getTPAToken($time, $key): string {
    $ciphertext = openssl_encrypt($time, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
    return urlencode(base64_encode($ciphertext));
  }

  /**
   * @return TrustedRedirectResponse
   */
  public function accessArchive(): TrustedRedirectResponse {
    $time = time()*1000;
    $key = $this->key;
    $token = $this->getTPAToken($time, $key);
    // $test_time = 1455825138000;
    // $test_key = 'EDQv1yYkcX1nglAf';
    // $token = $this->getTPAToken($test_time, $test_key);
    $url = 'https://' . $this->link . '.newspapers.com/?tpa=' . $token;
    $response = new TrustedRedirectResponse($url);
    /* We do not want the response cached */
    $cacheable_metadata = new CacheableMetadata();
    $cacheable_metadata->setCacheMaxAge(0);
    $response->addCacheableDependency($cacheable_metadata);
    return $response;
  }

}
