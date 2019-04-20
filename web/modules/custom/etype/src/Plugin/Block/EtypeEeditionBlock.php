<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Etype E-edition' Block.
 *
 * @Block(
 *   id = "etype_block",
 *   admin_label = @Translation("eType E-edition"),
 *   category = @Translation("eType"),
 * )
 */
class EtypeEeditionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $e_editions = etype_e_editions();
    $output = '';
    foreach ($e_editions as $e_edition) {
      $output .= '
<div><a href="' . $e_edition['path'] . '" target="_blank"><img src="' . $e_edition['image'] . '"></a></div>
<p><a href="' . $e_edition['path'] . '" target="_blank">Read ' . $e_edition['site_name'] . '</a></p>
';
    }
    return [
      '#markup' => $output,
    ];
  }

}
