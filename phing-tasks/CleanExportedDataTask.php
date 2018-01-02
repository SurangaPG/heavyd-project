<?php

require_once "phing/Task.php";

/**
 * Class CleanExportedDataTask
 *
 * This task will take all the data in a dir and clean it up for use in a more
 * generic default content setup. Since the standard HAL export tends to use
 * the uri of the machine exporting it which we currently can't rely on.
 */
class CleanExportedDataTask extends Task {

  /**
   * The active domain (uri) for the person doing the exporting.
   */
  private $activeDomain = null;

  /**
   * The dir where the content files are located.
   */
  private $defaultContentDir = null;

  /**
   * The setter for the attribute "message"
   */
  public function setActiveDomain($str) {
    $this->activeDomain = $str;
  }

  /**
   * The setter for the attribute "message"
   */
  public function setDefaultContentDir($str) {
    $this->defaultContentDir = $str;
  }

  /**
   * The init method: Do init steps.
   */
  public function init() {
    // nothing to do here
  }

  /**
   * The main entry point method.
   */
  public function main() {

/**
 * Very minimal helper to ensure that the exported data is cleansed out correctly.
 */
    // Currently active domain (where the export was handled).
    $activeDomain = $this->activeDomain;

    // Account for the char escaping in the export.
    $activeDomain = str_replace('/', '\/', $activeDomain);

    $domainPlaceholder = '[export:active_domain]';

    // We don't need user 1 since it's created at install time.
    $userFiles = glob($this->defaultContentDir . '/user/*.json');

    foreach ($userFiles as $userFile) {
      $data = json_decode(file_get_contents($userFile));

      if ($data->uid[0]->value == 1 || $data->uid[0]->value == 0) {
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove($userFile);
        print("  Deleted user/" . $data->uid[0]->value . " export: " . $userFile . "\n");
      }
    }
  }
}

?>