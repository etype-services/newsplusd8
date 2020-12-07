<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides an ADA Compliance Block.
 *
 * @Block(
 *   id = "ada_compliance_block",
 *   admin_label = @Translation("ADA Compliance"),
 *   category = @Translation("eType"),
 * )
 */
class AdaComplianceBlock extends BlockBase {

  /**
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build(): array {

    $output = '
<a href="/ada-compliance" aria-label="ADA Compliance Logo"><img src="/modules/custom/etype/img/ADACompliantLogo.jpg" alt="This site complies with ADA requirements" /></a>
    ';

    return [
      '#children' => Markup::create($output),
    ];
  }

}
