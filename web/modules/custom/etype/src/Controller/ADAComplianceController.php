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
      $mbd = new PDO('mysql:host=172.28.1.3', 'dbadmin', 'BLJPX3pihAJA1AquXpWzGhaGCZxtxxAD',
      [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'pdo' => [
          PDO::MYSQL_ATTR_SSL_KEY => '/etc/mysql/certs/client-key.pem',
          PDO::MYSQL_ATTR_SSL_CERT => '/etc/mysql/certs/client-cert.pem',
          PDO::MYSQL_ATTR_SSL_CA => '/etc/mysql/certs/server-ca.pem',
          PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => FALSE,
        ],
      ]
      );
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
