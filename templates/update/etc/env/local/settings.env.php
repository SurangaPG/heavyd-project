<?php

// Generate a dummy local hash
$dummy_hash = [];
exec('whoami', $dummy_hash);
$settings['hash_salt'] = base64_encode($dummy_hash[0] . __DIR__);

// Read the machine name from the current dir
$baseSiteName = basename(__DIR__);

/*
 * Read all the other data from the db properties.
 */
$dbPropertiesFile = dirname(dirname(dirname(__DIR__))) . '/properties/db.yml';

$dbPropertieSets = ['default' => ['default' => []]];
if (file_exists($dbPropertiesFile)) {
  $dbPropertieSets = \Symfony\Component\Yaml\Yaml::parse(
    file_get_contents($dbPropertiesFile)
  );
}

// Add default database settings if not given
foreach ($dbPropertieSets[$baseSiteName] as $key1 => $databaseList) {
  foreach ($databaseList as $key2 => $database) {
    // check if port is given
    if (!isset($dbPropertieSets[$baseSiteName][$key1][$key2]['port'])) {
      $dbPropertieSets[$baseSiteName][$key1][$key2]['port'] = 3306;
    }

    // check if driver is given
    if (!isset($dbPropertieSets[$baseSiteName][$key1][$key2]['driver'])) {
      $dbPropertieSets[$baseSiteName][$key1][$key2]['driver'] = 'mysql';
    }

    // check if namespace is given
    if (!isset($dbPropertieSets[$baseSiteName][$key1][$key2]['namespace'])) {
      $dbPropertieSets[$baseSiteName][$key1][$key2]['namespace'] = "Drupal\\Core\\Database\\Driver\\mysql";
    }
  }
}

// Set databases
$databases = $dbPropertieSets[$baseSiteName];
