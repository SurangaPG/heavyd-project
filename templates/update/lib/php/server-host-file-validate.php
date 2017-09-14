#!/usr/bin/env php

<?php

/**
 * Very minimal helper file to validate the existing .env file in an installation.
 *
 * @TODO This is absolute minimal MVP and should be ported to a decent phing task at some point.
 *
 * Requires the following arguments:
 *
 * Project root location:
 */

global $argv;

$fileLocation = $argv[1] . '/.host';

// File should exist.
if (!file_exists($fileLocation)) {
  echo "file " . $fileLocation . " not found";
  exit(1);
}

// Read the file and check or it's valid encoded json.
// e.g this should fail if this doesn't work.
$data = file_get_contents($fileLocation);
$data = json_decode($data);

if (json_last_error() !== JSON_ERROR_NONE) {
  echo "invalid json in " . $fileLocation;
  echo json_last_error_msg() . "\n";
  exit(1);
}



