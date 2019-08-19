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
# for drall updates
$sites['cni.etypegoogle7.com'] = "cni.etypegoogle7.com";
$sites['tiempos.etypegoogle7.com'] = "tiempos.etypegoogle7.com";

$sites['www.bayoujournal.com'] = 'bayoujournal.etypegoogle7.com';
$sites['www.bayoupioneer.com'] = 'bayoujournal.etypegoogle7.com';
$sites['www.giddingstimes.com'] = "giddingstimes.etypegoogle7.com";
$sites['www.ssnewstelegram.com'] = 'ssnewstelegram.etypegoogle7.com';
$sites['www.southwestledger.news'] = 'southwestledger.etypegoogle7.com';
$sites['www.etypeservices.com'] = 'etypeservices.etypegoogle7.com';
$sites['www.cordellbeacon.com'] = 'cordellbeacon.etypegoogle7.com';
$sites['www.madillrecord.net'] = 'madillrecord.etypegoogle7.com';
$sites['www.waltersherald.com'] = 'waltersherald.etypegoogle7.com';
$sites['www.lcherald.com'] = 'lcherald.etypegoogle7.com';
$sites['www.thedahloneganugget.com'] = 'thedahloneganugget.etypegoogle7.com';
$sites['www.theclaytontribune.com'] = 'theclaytontribune.etypegoogle7.com';
$sites['www.ouraynews.com'] = 'ouraynews.etypegoogle7.com';
$sites['www.cordellbeacon.com'] = 'cordellbeacon.etypegoogle7.com';
$sites['www.whitecountynews.net'] = 'whitecountynews.etypegoogle7.com';
$sites['www.bctribune.com'] = 'bctribune.etypegoogle7.com';
$sites['www.fairfield-recorder.com'] = 'fairfield-recorder.etypegoogle7.com';
$sites['www.thenortheastgeorgian.com'] = 'thenortheastgeorgian.etypegoogle7.com';
$sites['www.thehartwellsun.com'] = 'thehartwellsun.etypegoogle7.com';
$sites['www.poncacitynews.com'] = 'poncacitynews.etypegoogle7.com';
$sites['www.schulenburgsticker.com'] = 'schulenburgsticker.etypegoogle7.com';
$sites['www.thetoccoarecord.com'] = 'thetoccoarecord.etypegoogle7.com';
$sites['www.palatkadailynews.com'] = 'palatkadailynews.etypegoogle7.com';
$sites['www.bunabeacon.com'] = 'bunabeacon.etypegoogle7.com';
$sites['www.elrenotribune.com'] = 'elrenotribune.etypegoogle7.com';

/* jackie */
$sites['www.bellvilletimes.com'] = "bellvilletimes.etypegoogle11.com";
$sites['www.bookernews.com'] = "bookernews.etypegoogle11.com";
$sites['www.colfaxchronicle.com'] = "colfaxchronicle.etypegoogle11.com";
$sites['www.edenecho.net'] = "edenecho.etypegoogle11.com";
$sites['www.columbusnews-report.com'] = "columbusnews-report.etypegoogle11.com";
