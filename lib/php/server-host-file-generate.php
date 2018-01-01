#!/usr/bin/env php

<?php

/**
 * Very minimal helper file to generate a .host file for a build.
 *
 * @TODO This is absolute minimal MVP and should be ported to a decent phing task at some point.
 *
 * Requires the following arguments:
 *
 * - Project root location
 * - Project main domain
 * - Project allowed domains (comma separated string)
 *
 */

global $argc;
global $argv;

if ($argc !== 4) {
  echo "Incorrect of arguments (" . $argc . "), please provide: {Project root location}, {Project main domain} and {Project allowed domains} \n";
  exit(1);
}

$data = [
  'host' => $argv[2],
  'allowed_domains' => explode(',', $argv[3])
];

$hostFile = $argv[1] . '/.host';

$hostFile = fopen($hostFile, "w") or die("Unable to open $hostFile .\n");
fwrite($hostFile, json_encode($data, JSON_PRETTY_PRINT));