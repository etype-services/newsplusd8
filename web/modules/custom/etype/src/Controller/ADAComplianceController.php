<?php

namespace Drupal\etype\Controller;

use PDOException;

/**
 * Class ADAComplianceController.
 *
 * @package Drupal\etype\Controller
 */
class ADAComplianceController {

  /**
   * Class ADAComplianceController.
   *
   * @package Drupal\etype\Controller
   */
  public function adaCompliance() {
    try {
      $mbd = new PDO('mysql:host=localhost;', 'root', '');
      foreach ($mbd->query('SELECT * from FOO') as $fila) {
        print_r($fila);
      }
      $mbd = NULL;
    } catch PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

}
