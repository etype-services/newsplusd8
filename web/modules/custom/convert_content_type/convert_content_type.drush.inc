<?php
/**
* @file Contains the code to generate the custom drush command.
*/

/**
* Implements hook_drush_command().
*/
function convert_content_type_drush_command() {
 $items = [];
 $items['say-hello'] = [
   'description' => 'Echo the name you type with Say hello command',
   'arguments' => [
     'name' => 'The name for saying hello',
   ],
   'drupal dependencies' => ['convert_content_type'],
   'aliases' => ['say:hello'],
 ];
 return $items;
}

/**
* Call back function drush_convert_content_type_say_hello()
* The call back function name in the  following format
*   drush_{module_name}_{item_id_for_command}()
*/
function drush_convert_content_type_say_hello($name = '') {
 drush_print('Hello ' . $name . ' !');
}
