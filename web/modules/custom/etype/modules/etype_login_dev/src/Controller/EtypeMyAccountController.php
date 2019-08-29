<?php

namespace Drupal\etype_login_dev\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Class EtypeMyAccountController.
 *
 * @package Drupal\etype_login_dev\Controller
 */
class EtypeMyAccountController extends ControllerBase {

  /**
   * Returns a render-able array.
   */
  public function content() {

    $user_name = Drupal::currentUser()->getAccountName();
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    $build = [
      #'#children' => "<iframe src='http://etype.wecode4u.com/subscriber-account?pubId=" . $pubId . "&username=" . $user_name . "' width='100%' height='600' scrolling='vertical' title='My Account Update Form'></iframe>",
      '#children' => "<iframe src='http://www.wecode4u.com/' width='100%' height='600' scrolling='vertical' title='My Account Update Form'></iframe>",
    ];
    return $build;
  }

}
