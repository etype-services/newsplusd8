<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'eType E-edition' Block.
 *
 * @Block(
 *   id = "etype_block",
 *   admin_label = @Translation("eType E-edition"),
 *   category = @Translation("eType"),
 * )
 */

class eTypeEeditionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $e_editions = etype_e_editions();
    $output = '';
    foreach ($e_editions as $e_edition) {
      $output .= '
<p><a href="' . $e_edition['path'] . '" target="_blank"><img src="' . $e_edition['image'] . '"></a></p>
<p><a href="' . $e_edition['path'] . '" target="_blank">Click here to read ' . $e_edition['site_name'] . '</a></p>
';
    }
    return [
      '#markup' => $output,
    ];
  }

}