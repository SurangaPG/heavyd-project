<?php

/**
 * @file includes the needed settings form for a level27 server.
 */

$envFile = dirname(dirname(dirname(__DIR__))) . '/.env';

// Get the files from the .env file
$envConf = base64_decode(file_get_contents($envFile));
$envConf = json_decode($envConf, TRUE);

$databases = $envConf['database'];

$settings['hash_salt'] = $envConf['hash_salt'];