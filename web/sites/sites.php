<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 *
 * This file is required for multi-site support and also allows you to define a
 * set of aliases that map hostnames, ports, and pathnames to configuration
 * directories in the sites directory. These aliases are loaded prior to
 * scanning for directories, and they are exempt from the normal discovery
 * rules. See default.settings.php to view how Drupal discovers the
 * configuration directory when no alias is found.
 *
 * Aliases are useful on development servers, where the domain name may not be
 * the same as the domain of the live server. Since Drupal stores file paths in
 * the database (files, system table, etc.) this will ensure the paths are
 * correct when the site is deployed to a live server.
 *
 * To activate this feature, copy and rename it such that its path plus
 * filename is 'sites/sites.php'.
 *
 * Aliases are defined in an associative array named $sites. The array is
 * written in the format: '<port>.<domain>.<path>' => 'directory'. As an
 * example, to map https://www.drupal.org:8080/mysite/test to the configuration
 * directory sites/example.com, the array should be defined as:
 * @code
 * $sites = [
 *   '8080.www.drupal.org.mysite.test' => 'example.com',
 * ];
 * @endcode
 * The URL, https://www.drupal.org:8080/mysite/test/, could be a symbolic link
 * or an Apache Alias directive that points to the Drupal root containing
 * index.php. An alias could also be created for a subdomain. See the
 * @link https://www.drupal.org/documentation/install online Drupal installation guide @endlink
 * for more information on setting up domains, subdomains, and subdirectories.
 *
 * The following examples look for a site configuration in sites/example.com:
 * @code
 * URL: http://dev.drupal.org
 * $sites['dev.drupal.org'] = 'example.com';
 *
 * URL: http://localhost/example
 * $sites['localhost.example'] = 'example.com';
 *
 * URL: http://localhost:8080/example
 * $sites['8080.localhost.example'] = 'example.com';
 *
 * URL: https://www.drupal.org:8080/mysite/test/
 * $sites['8080.www.drupal.org.mysite.test'] = 'example.com';
 * @endcode
 *
 * @see default.settings.php
 * @see \Drupal\Core\DrupalKernel::getSitePath()
 * @see https://www.drupal.org/documentation/install/multi-site
 */

$sites['newsplusd8.local'] = 'newsplusd8.etypegoogle7.com';

$sites['cninewspapers.etypegoogle11.com'] = "cninewspapers.etypegoogle11.com";
$sites['www.cninewspapers.com'] = "cninewspapers.etypegoogle11.com";
$sites['cninewspapers.com'] = "cninewspapers.etypegoogle11.com";

$sites['myfamilydocga.etypegoogle11.com'] = "myfamilydocga.etypegoogle11.com";
$sites['www.myfamilydocga.com'] = "myfamilydocga.etypegoogle11.com";
$sites['myfamilydocga.com'] = "myfamilydocga.etypegoogle11.com";

$sites['taniafrench.etypegoogle11.com'] = "taniafrench.etypegoogle11.com";
$sites['www.taniafrench.com'] = "taniafrench.etypegoogle11.com";
$sites['taniafrench.com'] = "taniafrench.etypegoogle11.com";

$sites['netxcrossroads.etypegoogle11.com'] = "netxcrossroads.etypegoogle11.com";
$sites['www.netxcrossroads.com'] = "netxcrossroads.etypegoogle11.com";
$sites['netxcrossroads.com'] = "netxcrossroads.etypegoogle11.com";

$sites['bellvilletimes.etypegoogle11.com'] = "bellvilletimes.etypegoogle11.com";
$sites['www.bellvilletimes.com'] = "bellvilletimes.etypegoogle11.com";
$sites['bellvilletimes.com'] = "bellvilletimes.etypegoogle11.com";

$sites['66hoursingrants.etypegoogle11.com'] = "66hoursingrants.etypegoogle11.com";
$sites['www.66hoursingrants.com'] = "66hoursingrants.etypegoogle11.com";
$sites['66hoursingrants.com'] = "66hoursingrants.etypegoogle11.com";

$sites['goodnewspress.etypegoogle11.com'] = "goodnewspress.etypegoogle11.com";
$sites['www.goodnewspress.com'] = "goodnewspress.etypegoogle11.com";
$sites['goodnewspress.com'] = "goodnewspress.etypegoogle11.com";

$sites['hoxietimes.etypegoogle11.com'] = "hoxietimes.etypegoogle11.com";
$sites['www.hoxietimes.com'] = "hoxietimes.etypegoogle11.com";
$sites['hoxietimes.com'] = "hoxietimes.etypegoogle11.com";

$sites['kindernow.etypegoogle11.com'] = "kindernow.etypegoogle11.com";
$sites['www.kindernow.com'] = "kindernow.etypegoogle11.com";
$sites['kindernow.com'] = "kindernow.etypegoogle11.com";

$sites['caldwellwatchman.etypegoogle11.com'] = "caldwellwatchman.etypegoogle11.com";
$sites['www.caldwellwatchman.com'] = "caldwellwatchman.etypegoogle11.com";
$sites['caldwellwatchman.com'] = "caldwellwatchman.etypegoogle11.com";

$sites['westcarrollgazette.etypegoogle11.com'] = "westcarrollgazette.etypegoogle11.com";
$sites['www.westcarrollgazette.com'] = "westcarrollgazette.etypegoogle11.com";
$sites['westcarrollgazette.com'] = "westcarrollgazette.etypegoogle11.com";

$sites['bayoujournal.etypegoogle11.com'] = "bayoujournal.etypegoogle11.com";
$sites['www.bayoujournal.com'] = "bayoujournal.etypegoogle11.com";
$sites['bayoujournal.com'] = "bayoujournal.etypegoogle11.com";

$sites['sabinecountyreporter.etypegoogle11.com'] = "sabinecountyreporter.etypegoogle11.com";
$sites['www.sabinecountyreporter.com'] = "sabinecountyreporter.etypegoogle11.com";
$sites['sabinecountyreporter.com'] = "sabinecountyreporter.etypegoogle11.com";

$sites['themillerpress.etypegoogle11.com'] = "themillerpress.etypegoogle11.com";
$sites['www.themillerpress.com'] = "themillerpress.etypegoogle11.com";
$sites['themillerpress.com'] = "themillerpress.etypegoogle11.com";

$sites['theravennanews.etypegoogle11.com'] = "theravennanews.etypegoogle11.com";
$sites['www.theravennanews.com'] = "theravennanews.etypegoogle11.com";
$sites['theravennanews.com'] = "theravennanews.etypegoogle11.com";

$sites['thekansascityglobe.etypegoogle11.com'] = "thekansascityglobe.etypegoogle11.com";
$sites['www.thekansascityglobe.com'] = "thekansascityglobe.etypegoogle11.com";
$sites['thekansascityglobe.com'] = "thekansascityglobe.etypegoogle11.com";

$sites['thekcglobe.etypegoogle11.com'] = "thekansascityglobe.etypegoogle11.com";
$sites['www.thekcglobe.com'] = "thekansascityglobe.etypegoogle11.com";
$sites['thekcglobe.com'] = "thekansascityglobe.etypegoogle11.com";

$sites['phonographherald.etypegoogle11.com'] = "phonographherald.etypegoogle11.com";
$sites['www.phonographherald.com'] = "phonographherald.etypegoogle11.com";
$sites['phonographherald.com'] = "phonographherald.etypegoogle11.com";

$sites['edenecho.etypegoogle11.com'] = "edenecho.etypegoogle11.com";
$sites['www.edenecho.net'] = "edenecho.etypegoogle11.com";
$sites['edenecho.net'] = "edenecho.etypegoogle11.com";

$sites['normangeestar.etypegoogle11.com'] = "normangeestar.etypegoogle11.com";
$sites['www.normangeestar.com'] = "normangeestar.etypegoogle11.com";
$sites['normangeestar.com'] = "normangeestar.etypegoogle11.com";

$sites['lulingnewsboy.etypegoogle11.com'] = "lulingnewsboy.etypegoogle11.com";
$sites['www.lulingnewsboy.com'] = "lulingnewsboy.etypegoogle11.com";
$sites['lulingnewsboy.com'] = "lulingnewsboy.etypegoogle11.com";

$sites['lavacacountytoday.etypegoogle11.com'] = "lavacacountytoday.etypegoogle11.com";
$sites['www.lavacacountytoday.com'] = "lavacacountytoday.etypegoogle11.com";
$sites['lavacacountytoday.com'] = "lavacacountytoday.etypegoogle11.com";

$sites['whitneytheatre.etypegoogle11.com'] = "whitneytheatre.etypegoogle11.com";
$sites['www.whitneytheatre.com'] = "whitneytheatre.etypegoogle11.com";
$sites['whitneytheatre.com'] = "whitneytheatre.etypegoogle11.com";

$sites['texas-grass.etypegoogle11.com'] = "texas-grass.etypegoogle11.com";
$sites['www.texas-grass.com'] = "texas-grass.etypegoogle11.com";
$sites['texas-grass.com'] = "texas-grass.etypegoogle11.com";

$sites['bookernews.etypegoogle11.com'] = "bookernews.etypegoogle11.com";
$sites['www.bookernews.com'] = "bookernews.etypegoogle11.com";
$sites['bookernews.com'] = "bookernews.etypegoogle11.com";

$sites['wespubco.etypegoogle11.com'] = "wespubco.etypegoogle11.com";
$sites['www.wespubco.com'] = "wespubco.etypegoogle11.com";
$sites['wespubco.com'] = "wespubco.etypegoogle11.com";
$sites['www.wesnerpublications.com'] = "wespubco.etypegoogle11.com";
$sites['wesnerpublications.com'] = "wespubco.etypegoogle11.com";

$sites['mosercommedia.etypegoogle11.com'] = "mosercommedia.etypegoogle11.com";
$sites['www.mosercommedia.com'] = "mosercommedia.etypegoogle11.com";
$sites['mosercommedia.com'] = "mosercommedia.etypegoogle11.com";

$sites['giddingstimes.etypegoogle11.com'] = "giddingstimes.etypegoogle11.com";
$sites['www.giddingstimes.com'] = "giddingstimes.etypegoogle11.com";
$sites['giddingstimes.com'] = "giddingstimes.etypegoogle11.com";

$sites['colfaxchronicle.etypegoogle11.com'] = "colfaxchronicle.etypegoogle11.com";
$sites['www.colfaxchronicle.com'] = "colfaxchronicle.etypegoogle11.com";
$sites['colfaxchronicle.com'] = "colfaxchronicle.etypegoogle11.com";

$sites['columbusnews-report.etypegoogle11.com'] = "columbusnews-report.etypegoogle11.com";
$sites['www.columbusnews-report.com'] = "columbusnews-report.etypegoogle11.com";
$sites['columbusnews-report.com'] = "columbusnews-report.etypegoogle11.com";

$sites['rileycountian.etypegoogle11.com'] = "rileycountian.etypegoogle11.com";
$sites['www.rileycountian.com'] = "rileycountian.etypegoogle11.com";
$sites['rileycountian.com'] = "rileycountian.etypegoogle11.com";

$sites['unwindnorthgeorgia.etypegoogle11.com'] = "ung.etypegoogle11.com";
$sites['www.unwindnorthgeorgia.com'] = "ung.etypegoogle11.com";
$sites['unwindnorthgeorgia.com'] = "ung.etypegoogle11.com";

