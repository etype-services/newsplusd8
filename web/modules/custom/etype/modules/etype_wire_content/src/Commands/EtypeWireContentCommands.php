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
   * @command etype_wire_content:export
   * @aliases wire-export
   * @usage etype_wire_content:export
   *   Run Export.
   */
  public function etypeWireContent() {

    try {
      (new WireContentExportController)->exportWireContent();
    }
    catch (InvalidPluginDefinitionException $e) {
    }
    catch (PluginNotFoundException $e) {
    }
  }

}
