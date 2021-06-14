<?php

namespace Drupal\etype\Plugin\Block;

use Drupal;
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

  public function build(): array {
    $e_editions = etype_e_editions();
    $logged_in = Drupal::currentUser()->isAuthenticated();
    $class = '';
    if ($logged_in > 0) {
      $class = ' class="etype_logged_in"';
    }
    $output = '';
    foreach ($e_editions as $e_edition) {
      $output .= '<div class="e-edition"><div><a href="' . $e_edition['path'] . '" target="_blank" rel="noreferrer" aria-label="' .
        $e_edition['site_name'] . ' e-Edition"' . $class . '><img src="' .
        $e_edition['image'] . '" alt="' . $e_edition['site_name'] . ' e-Edition"></a></div>
<p><a href="' . $e_edition['path'] . '" target="_blank" rel="noreferrer" aria-label="' . $e_edition['site_name'] . ' e-Edition"' . $class . '>Read ' . $e_edition['site_name'] . '</a></p></div>
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
  public function getCacheMaxAge() {
    return 0;
  }

}
