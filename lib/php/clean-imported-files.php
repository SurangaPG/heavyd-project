<?php

/**
 * Very minimal helper to ensure the correct file data after importing.
 *
 * @TODO This is absolute minimal MVP and should be ported to a decent phing task at some point.
 */

/** @var \Drupal\file\FileInterface[] $files */
$files = \Drupal\file\Entity\File::loadMultiple();

// The location of the files is not set correctly when importing. Since this is
// normally done by the form submit handlers. To remedy this we'll reset it
// "manually" based on the data provided in the export which contains the
// expected location in the file system.
foreach ($files as $file) {
  $file->setPermanent();

  // Get the location based on the export.
  $fileName = sprintf('/Users/suranga/Webct/werkhuys.local/web/modules/custom/baseline_content/content/file/%s.json', $file->uuid());
  if (file_exists($fileName)) {
    $data = json_decode(file_get_contents($fileName));

    // Get the original location for the file.
    // e.g: http:\/\/werkhuys.mamp\/sites\/default\/files\/zalen\/klaslokaal-3-1.jpg
    $uri = $data->uri[0]->value;

    // Prevent trying to do this twice.
    if (strpos($uri, 'public://') === 0) {
      continue;
    }

    explode('\/', $uri);
    array_shift($uri); // - http:
    array_shift($uri); // - NULL (due to \/\/)
    array_shift($uri); // - sites
    array_shift($uri); // - default
    array_shift($uri); // - files

    array_pop($uri);

    $uri = implode('/', $uri);
    $uri = "public://" . $uri . '/' . $file->getFilename();
    // Is now the expected public://zalen/klaslokaal-3-1.jpg

    $file->setFileUri($uri);
  }

  $file->save();
}