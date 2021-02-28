<?php

namespace Drupal\etype_pico\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Etype Pico E-edition' Block.
 *
 * @Block(
 *   id = "etype_pico_block",
 *   admin_label = @Translation("eType Pico E-edition"),
 *   category = @Translation("eType"),
 * )
 */
class EtypePicoEeditionBlock extends BlockBase {

  public function build(): array {
    $e_editions = etype_e_editions();
    $output = '';
    foreach ($e_editions as $e_edition) {
      $output .= '<div class="e-edition PicoSignal"><div><a class="PicoPlan" href="' . $e_edition['path'] . '" target="_blank" aria-label="' .
        $e_edition['site_name'] . ' e-Edition"' . '><img src="' .
        $e_edition['image'] . '" alt="' . $e_edition['site_name'] . ' e-Edition"></a></div>
<p><a class="PicoPlan" href="' . $e_edition['path'] . '" target="_blank" aria-label="' . $e_edition['site_name'] . ' e-Edition"' . '>Read ' . $e_edition['site_name'] . '</a></p></div>
';
    }
    return [
      '#markup' => $output,
    ];
  }

  /**
   * Disable Caching.
   *
   * @return int
   *   Boolean
   */
  public function getCacheMaxAge(): int {
    return 0;
  }

}
