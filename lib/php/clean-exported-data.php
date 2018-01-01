<?php

/**
 * Very minimal helper to ensure that the exported data is cleansed out correctly.
 */

// Currently active domain (where the export was handled).
$activeDomain = 'http:\/\/werkhuys.mamp';
$domainPlaceholder = '[export:active_domain]';

// We don't need user 1 since it's created at install time.
$userFiles = glob('/Users/suranga/Webct/werkhuys.local/web/modules/custom/baseline_content/content/user/*.json');

foreach ($userFiles as $userFile) {
  $data = json_decode(file_get_contents($userFile));

  if ($data->uid[0]->value == 1 || $data->uid[0]->value == 0) {
    $fs = new \Symfony\Component\Filesystem\Filesystem();
    $fs->remove($userFile);
    echo "  Deleted user/" .  $data->uid[0]->value . " export: " . $userFile . "\n";
    break;
  }
}
