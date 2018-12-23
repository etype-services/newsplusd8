<?php

/**
 * @file
 * Contains
 * Drupal\etype_xml_importer\Controller\ImportOliveXMLController
 */

namespace Drupal\etype_xml_importer\Controller;

/**
 * @inheritdoc
 */
class ImportOliveXMLController {

  /**
   * @inheritdoc
   */
  public function ImportOliveXml() {
    $markup = 'test';
    return array(
      '#markup' => $markup,
    );
  }

}

