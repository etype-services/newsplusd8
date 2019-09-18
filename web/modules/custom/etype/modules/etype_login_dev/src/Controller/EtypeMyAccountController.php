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
   * Returns a renderable array.
   */
  public function content() {

    $user_name = Drupal::currentUser()->getAccountName();
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    $build = [
      '#children' => "<script type='text/javascript'>
                window.addEventListener('message', receiveMessage, false);
                function receiveMessage(event) {
                    var ifrmAccountUpdateForm = document.getElementById('ifrmAccountUpdateForm');
		    if (event.data.docHeight != undefined && event.data.docHeight != 'undefined')
                        ifrmAccountUpdateForm.height = event.data.docHeight;
                }
            </script><iframe id='ifrmAccountUpdateForm' src='http://etype.wecode4u.com/subscriber-account?pubId=" . $pubId . "&username=" . $user_name . "' width='100%' height='860' frameborder='0' scrolling='no'></iframe>",
    ];
    return $build;
  }

}

