#!/usr/bin/env php

<?php
/**
 * Very minimal helper to ensure the correct uri when importing.
 *
 * @TODO This is absolute minimal MVP and should be ported to a decent phing task at some point.
 */

global $argv;

$webRoot = rtrim($argv[1], '/');
$uri = rtrim($argv[2], '/');

$activeDomain = str_replace('/', '\/', $uri);
$domainPlaceholder = '[export:active_domain]';

// The location of the files is not set correctly when importing. Since this is
// normally done by the form submit handlers. To remedy this we'll reset it
// "manually" based on the data provided in the export which contains the
// expected location in the file system.
// The standard export uses a hal schema which links to the local site.
// Since this might be different for different developers/environments we'll
// cleanse it out here.

echo "Replacing placeholder with active domain. \n";

$exportedFiles = glob($webRoot . '/modules/custom/baseline_content/content/*/*.json');

foreach ($exportedFiles as $exportedFile) {
  $data = file_get_contents($exportedFile);
  $data = str_replace($domainPlaceholder, $activeDomain, $data);
  file_put_contents($exportedFile, $data);
}