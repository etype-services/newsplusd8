<?php

namespace Drupal\etype_login_v2\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class EtypeV2MyAccountController.
 *
 * @package Drupal\etype_login_v2\Controller
 */
class EtypeV2MyAccountController extends ControllerBase {

  /**
   * Returns a renderable array.
   */
  public function content() {

    $user_name = Drupal::currentUser()->getAccountName();
    $config = Drupal::config('etype.adminsettings');
    $pubId = (int) $config->get('etype_pub');
    return [
      '#children' => "<script type='text/javascript'>
                window.addEventListener('message', receiveMessage, false);
                function receiveMessage(event) {
                    let ifrmAccountUpdateForm = document.getElementById('ifrmAccountUpdateForm');
		    if (event.data.docHeight != undefined && event.data.docHeight != 'undefined')
                        ifrmAccountUpdateForm.height = event.data.docHeight;
                }
            </script><iframe id='ifrmAccountUpdateForm' src='https://publisher.etype.services/subscriber-account?pubId=" . $pubId . "&username=" . $user_name . "' width='100%' height='860' frameborder='0' scrolling='no'></iframe>",
    ];
  }

}
