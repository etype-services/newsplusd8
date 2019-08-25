<?php

namespace Drupal\etype\Controller;

use PDOException;
use PDO;

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

    $output = '';
    $fila = [];

    try {
      $mbd = new PDO('mysql:host=localhost', 'root', 'fF-N}^@h]uWpw%%>eL~#2o&[0}=;=');
      foreach ($mbd->query('show databases') as $fila) {
        $output .= var_export($fila["Database"], TRUE);
      }
    }
    catch (PDOException $e) {
      print "¡Error!: " . $e->getMessage() . "<br/>";
      die();
    }

    $fixed = array_unique($fila);

    foreach ($fixed as $item) {
      foreach ($mbd->query("SELECT `body_value` FROM $item.block_content__body WHERE `body_value` REGEXP '<iframe[^>]*>'") as $row) {
        $output .= var_export($row["body_value"], TRUE);
      }
    }

    $build = [
      '#markup' => $output,
    ];

    return $build;
  }

}
