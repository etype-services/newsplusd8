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
  function etype_e_editions() {

    $logged_in = \Drupal::currentUser()->isAuthenticated();
    $config = \Drupal::config('etype.adminsettings');
    $e_edition = $config->get('etype_e_edition');
    $pub = $config->get('etype_pub');
    $ptype = $config->get('etype_ptype');

    $config = \Drupal::config('system.site');
    $site = $config->get('name');

    /* because Lutcher has a comma */
    if (strpos($e_edition, '|') !== false) {
      $items = explode(',', $e_edition);
      $pubs = explode(',', $pub);
      $ptypes = explode(',', $ptype);
    } else {
      $items = [$e_edition];
      $pubs = [$pub];
      $ptypes = [$ptype];
    }

    $e_editions = [];
    $ptr = 0;
    foreach ($items as $item) {
      $arr = explode('|', $item);
      if (isset($arr[1])) {
        $site = trim($arr[1]);
      }

      $ar2 = preg_split("/ID[0-9]+/", $arr[0]); // make LandingImage directory name
      $imagedir = trim($ar2[0]);
      $e_editions[$ptr]['image'] = 'https://etypeservices.com/LandingPageImages/' . $imagedir . '/currentpg1.jpg';

      if (isset($pubs[$ptr])) {
        $pub = trim($pubs[$ptr]);
      }
      if (isset($ptypes[$ptr])) {
        $ptype = trim($ptypes[$ptr]);
      }
      $e_edition = trim($arr[0]);

      if ($logged_in > 0) {
        if (!empty ($pub)) {
          $account = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
          $name = $account->get('name')->value;
          $path = 'https://etypeservices.com/ReadTheEdition.aspx?Username=' . $name . "&Pub=" . $pub . "&PType=" . $ptype;
        } else {
          $path = 'https://etypeservices.com/' . $e_edition . '/';
        }
      } else {
        $path = 'https://etypeservices.com/' . $e_edition . '/';
      }

      $e_editions[$ptr]['site_name'] = trim($site);
      $e_editions[$ptr]['path'] = $path;
      $ptr++;
    }
    return $e_editions;
  }


  /**
   * {@inheritdoc}
   */
  public function build() {

    $e_editions = $this->etype_e_editions();
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