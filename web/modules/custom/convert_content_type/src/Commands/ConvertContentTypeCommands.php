<?php

namespace Drupal\convert_content_type\Commands;

use Drush\Commands\DrushCommands;
// use Drupal\Core\Entity\Sql\TableMappingInterface;
use Drupal\Core\Database\Database;

/**
 * A Drush commandfile.
 *
 * See http://www.noreiko.com/blog/changing-type-node
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *
 * - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php.
 *
 * - http://cgit.drupalcode.org/devel/tree/drush.services.yml.
 */
class ConvertContentTypeCommands extends DrushCommands {

  /**
   * Convert nodes to Archive type.
   *
   * @validate-module-enabled convert_content_type
   * @command convert:nodes
   * @aliases convert:nodes,convert-nodes
   */
  public function convert() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9
    // for details on what to change when porting a
    // legacy command.
    $storage = \Drupal::service('entity_type.manager')->getStorage('node');

    // Get the names of the base tables.
    $base_table_names = [];
    $base_table_names[] = $storage->getBaseTable();
    $base_table_names[] = $storage->getDataTable();

    // Get the names of the field tables for fields on the service node type.
    $field_table_names = [];
    $entityManager = \Drupal::service('entity_field.manager');
    $source_bundle_fields = $entityManager->getFieldDefinitions('node', 'article');
    $db = Database::getConnection();
    foreach ($source_bundle_fields as $field) {

      $field_table = 'node__' . $field->getName();
      // Field revision tables DO have the bundle!
      $field_revision_table = 'node_revision__' . $field->getName();
      // drush_print($field_table);
      $check = $db->schema()->tableExists($field_table);
      if ($check == 1) {
        $field_table_names[] = $field_table;
      }
      $check = $db->schema()->tableExists($field_revision_table);
      if ($check == 1) {
        $field_table_names[] = $field_revision_table;
      }
    }
    drush_print(print_r($field_table_names, TRUE));

    // Base tables have 'nid' and 'type' columns.
    foreach ($base_table_names as $table_name) {
      $query = $db->update($table_name)
        ->fields(['type' => 'archive'])
        ->condition('type', 'article', '=')
        ->execute();
    }
    // Field tables have 'entity_id' and 'bundle' columns.
    foreach ($field_table_names as $table_name) {
      $query = $db->update($table_name)
        ->fields(['bundle' => 'archive'])
        ->condition('bundle', 'article', '=')
        ->execute();
    }

  }

}

