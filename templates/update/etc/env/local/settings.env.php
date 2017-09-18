<?php

/**
 * @file includes the needed settings form for a default setup.
 *
 * The default setup assumes 2 things:
 *
 * A .env file in the root of the project (e.g one step above the webroot) which
 * contains all the data for serverbased connections.
 * Such as the DB etc. This file should contain a base_64 encoded base string.
 *
 * A .host file in the root of the project (e.g one step above the webroot) which
 * contains all the data for the host based protection etc. This should be a
 * json formatted file.
 * Note that the heavyd deploy will generate this for you at build time.
 *
 * The main distinctions between both files are:
 * - .env contains sensitive serverspecific data (passwords etc) and should never move of server
 * - .host contains "semi" serverspecific data such as Domains etc and should be able to migrate but is still
 *   defined on the server level.
 */

// Get the DB info etc from the .env file
$envFile = dirname(dirname(dirname(__DIR__))) . '/.env';

$envConf = file_get_contents($envFile);
$envConf = json_decode($envConf, TRUE);

$databases = $envConf['database'];

$settings['hash_salt'] = $envConf['hash_salt'];

// Get the extra information from the host file.
// @TODO Finish this.
$hostFile = dirname(dirname(dirname(__DIR__))) . '/.host';
$hostConf = json_decode(file_get_contents($hostFile), TRUE);