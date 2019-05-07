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

$sites['www.bayoujournal.com'] = 'bayoujournal.etypegoogle7.com';
$sites['www.bayoupioneer.com'] = 'bayoujournal.etypegoogle7.com';

$sites['cninewspapers.etypegoogle7.com'] = "cninewspapers.etypegoogle7.com";
$sites['www.cninewspapers.com'] = "cninewspapers.etypegoogle7.com";
$sites['cninewspapers.com'] = "cninewspapers.etypegoogle7.com";

$sites['myfamilydocga.etypegoogle7.com'] = "myfamilydocga.etypegoogle7.com";
$sites['www.myfamilydocga.com'] = "myfamilydocga.etypegoogle7.com";
$sites['myfamilydocga.com'] = "myfamilydocga.etypegoogle7.com";

$sites['taniafrench.etypegoogle7.com'] = "taniafrench.etypegoogle7.com";
$sites['www.taniafrench.com'] = "taniafrench.etypegoogle7.com";
$sites['taniafrench.com'] = "taniafrench.etypegoogle7.com";

$sites['netxcrossroads.etypegoogle7.com'] = "netxcrossroads.etypegoogle7.com";
$sites['www.netxcrossroads.com'] = "netxcrossroads.etypegoogle7.com";
$sites['netxcrossroads.com'] = "netxcrossroads.etypegoogle7.com";

$sites['bellvilletimes.etypegoogle7.com'] = "bellvilletimes.etypegoogle7.com";
$sites['www.bellvilletimes.com'] = "bellvilletimes.etypegoogle7.com";
$sites['bellvilletimes.com'] = "bellvilletimes.etypegoogle7.com";

$sites['66hoursingrants.etypegoogle7.com'] = "66hoursingrants.etypegoogle7.com";
$sites['www.66hoursingrants.com'] = "66hoursingrants.etypegoogle7.com";
$sites['66hoursingrants.com'] = "66hoursingrants.etypegoogle7.com";

$sites['goodnewspress.etypegoogle7.com'] = "goodnewspress.etypegoogle7.com";
$sites['www.goodnewspress.com'] = "goodnewspress.etypegoogle7.com";
$sites['goodnewspress.com'] = "goodnewspress.etypegoogle7.com";

$sites['hoxietimes.etypegoogle7.com'] = "hoxietimes.etypegoogle7.com";
$sites['www.hoxietimes.com'] = "hoxietimes.etypegoogle7.com";
$sites['hoxietimes.com'] = "hoxietimes.etypegoogle7.com";

$sites['kindernow.etypegoogle7.com'] = "kindernow.etypegoogle7.com";
$sites['www.kindernow.com'] = "kindernow.etypegoogle7.com";
$sites['kindernow.com'] = "kindernow.etypegoogle7.com";

$sites['caldwellwatchman.etypegoogle7.com'] = "caldwellwatchman.etypegoogle7.com";
$sites['www.caldwellwatchman.com'] = "caldwellwatchman.etypegoogle7.com";
$sites['caldwellwatchman.com'] = "caldwellwatchman.etypegoogle7.com";

$sites['westcarrollgazette.etypegoogle7.com'] = "westcarrollgazette.etypegoogle7.com";
$sites['www.westcarrollgazette.com'] = "westcarrollgazette.etypegoogle7.com";
$sites['westcarrollgazette.com'] = "westcarrollgazette.etypegoogle7.com";

$sites['bayoujournal.etypegoogle7.com'] = "bayoujournal.etypegoogle7.com";
$sites['www.bayoujournal.com'] = "bayoujournal.etypegoogle7.com";
$sites['bayoujournal.com'] = "bayoujournal.etypegoogle7.com";

$sites['sabinecountyreporter.etypegoogle7.com'] = "sabinecountyreporter.etypegoogle7.com";
$sites['www.sabinecountyreporter.com'] = "sabinecountyreporter.etypegoogle7.com";
$sites['sabinecountyreporter.com'] = "sabinecountyreporter.etypegoogle7.com";

$sites['themillerpress.etypegoogle7.com'] = "themillerpress.etypegoogle7.com";
$sites['www.themillerpress.com'] = "themillerpress.etypegoogle7.com";
$sites['themillerpress.com'] = "themillerpress.etypegoogle7.com";

$sites['theravennanews.etypegoogle7.com'] = "theravennanews.etypegoogle7.com";
$sites['www.theravennanews.com'] = "theravennanews.etypegoogle7.com";
$sites['theravennanews.com'] = "theravennanews.etypegoogle7.com";

$sites['thekansascityglobe.etypegoogle7.com'] = "thekansascityglobe.etypegoogle7.com";
$sites['www.thekansascityglobe.com'] = "thekansascityglobe.etypegoogle7.com";
$sites['thekansascityglobe.com'] = "thekansascityglobe.etypegoogle7.com";

$sites['thekcglobe.etypegoogle7.com'] = "thekansascityglobe.etypegoogle7.com";
$sites['www.thekcglobe.com'] = "thekansascityglobe.etypegoogle7.com";
$sites['thekcglobe.com'] = "thekansascityglobe.etypegoogle7.com";

$sites['phonographherald.etypegoogle7.com'] = "phonographherald.etypegoogle7.com";
$sites['www.phonographherald.com'] = "phonographherald.etypegoogle7.com";
$sites['phonographherald.com'] = "phonographherald.etypegoogle7.com";

$sites['edenecho.etypegoogle7.com'] = "edenecho.etypegoogle7.com";
$sites['www.edenecho.net'] = "edenecho.etypegoogle7.com";
$sites['edenecho.net'] = "edenecho.etypegoogle7.com";

$sites['normangeestar.etypegoogle7.com'] = "normangeestar.etypegoogle7.com";
$sites['www.normangeestar.com'] = "normangeestar.etypegoogle7.com";
$sites['normangeestar.com'] = "normangeestar.etypegoogle7.com";

$sites['lulingnewsboy.etypegoogle7.com'] = "lulingnewsboy.etypegoogle7.com";
$sites['www.lulingnewsboy.com'] = "lulingnewsboy.etypegoogle7.com";
$sites['lulingnewsboy.com'] = "lulingnewsboy.etypegoogle7.com";

$sites['lavacacountytoday.etypegoogle7.com'] = "lavacacountytoday.etypegoogle7.com";
$sites['www.lavacacountytoday.com'] = "lavacacountytoday.etypegoogle7.com";
$sites['lavacacountytoday.com'] = "lavacacountytoday.etypegoogle7.com";

$sites['whitneytheatre.etypegoogle7.com'] = "whitneytheatre.etypegoogle7.com";
$sites['www.whitneytheatre.com'] = "whitneytheatre.etypegoogle7.com";
$sites['whitneytheatre.com'] = "whitneytheatre.etypegoogle7.com";

$sites['texas-grass.etypegoogle7.com'] = "texas-grass.etypegoogle7.com";
$sites['www.texas-grass.com'] = "texas-grass.etypegoogle7.com";
$sites['texas-grass.com'] = "texas-grass.etypegoogle7.com";

$sites['bookernews.etypegoogle7.com'] = "bookernews.etypegoogle7.com";
$sites['www.bookernews.com'] = "bookernews.etypegoogle7.com";
$sites['bookernews.com'] = "bookernews.etypegoogle7.com";

$sites['wespubco.etypegoogle7.com'] = "wespubco.etypegoogle7.com";
$sites['www.wespubco.com'] = "wespubco.etypegoogle7.com";
$sites['wespubco.com'] = "wespubco.etypegoogle7.com";
$sites['www.wesnerpublications.com'] = "wespubco.etypegoogle7.com";
$sites['wesnerpublications.com'] = "wespubco.etypegoogle7.com";

$sites['mosercommedia.etypegoogle7.com'] = "mosercommedia.etypegoogle7.com";
$sites['www.mosercommedia.com'] = "mosercommedia.etypegoogle7.com";
$sites['mosercommedia.com'] = "mosercommedia.etypegoogle7.com";

$sites['giddingstimes.etypegoogle7.com'] = "giddingstimes.etypegoogle7.com";
$sites['www.giddingstimes.com'] = "giddingstimes.etypegoogle7.com";
$sites['giddingstimes.com'] = "giddingstimes.etypegoogle7.com";

$sites['colfaxchronicle.etypegoogle7.com'] = "colfaxchronicle.etypegoogle7.com";
$sites['www.colfaxchronicle.com'] = "colfaxchronicle.etypegoogle7.com";
$sites['colfaxchronicle.com'] = "colfaxchronicle.etypegoogle7.com";

$sites['columbusnews-report.etypegoogle7.com'] = "columbusnews-report.etypegoogle7.com";
$sites['www.columbusnews-report.com'] = "columbusnews-report.etypegoogle7.com";
$sites['columbusnews-report.com'] = "columbusnews-report.etypegoogle7.com";

$sites['rileycountian.etypegoogle7.com'] = "rileycountian.etypegoogle7.com";
$sites['www.rileycountian.com'] = "rileycountian.etypegoogle7.com";
$sites['rileycountian.com'] = "rileycountian.etypegoogle7.com";

$sites['unwindnorthgeorgia.etypegoogle7.com'] = "ung.etypegoogle7.com";
$sites['www.unwindnorthgeorgia.com'] = "ung.etypegoogle7.com";
$sites['unwindnorthgeorgia.com'] = "ung.etypegoogle7.com";

$sites['www.ssnewstelegram.com'] = 'ssnewstelegram.etypegoogle7.com';
$sites['ssnewstelegram.etypegoogle7.com'] = 'ssnewstelegram.etypegoogle7.com';
