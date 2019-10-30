<?php

namespace Drupal\etype_wire_content\Commands;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\etype_wire_content\Controller\WireContentExportController;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 */
class EtypeWireContentCommands extends DrushCommands {

  /**
   * Runs Wire Content Export.
   *
   * @validate-module-enabled etype_wire_content
   * @command etype_wire_content:export
   * @aliases etype_wire_content:export,wire-export,ewe
   */
  public function export() {

    try {
      (new WireContentExportController)->exportWireContent();
    }
    catch (InvalidPluginDefinitionException $e) {
    }
    catch (PluginNotFoundException $e) {
    }
  }

}
