<?php

namespace Drupal\etype\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a Blue Orchid Ad Block.
 *
 * @Block(
 *   id = "blue_orchid_46860",
 *   admin_label = @Translation("Blue Orchid 468x60"),
 *   category = @Translation("eType"),
 * )
 */
class BlueOrchid468x60 extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * Uses Markup rather than '#markup' to return raw html.
   */
  public function build() {
    $output = '
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6055882063795349";
/* EType_RON */
google_ad_slot = "EType_RON";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
    return [
      '#children' => Markup::create($output),
    ];
  }

}
