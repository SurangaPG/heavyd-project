<?php

require_once "phing/Task.php";

/**
 * Class PrepareExportedDataForImportTask
 *
 * This task will clean up all the data and make it entirely ready for importing
 * based on your local site.
 */
class PrepareExportedDataForImportTask extends Task {

  /**
   * The active domain (uri) for the person doing the exporting.
   */
  private $activeDomain = null;

  /**
   * The dir where the content files are located.
   */
  private $webRoot = null;

  /**
   * The setter for the attribute "message"
   */
  public function setActiveDomain($str) {
    $this->activeDomain = $str;
  }

  /**
   * The setter for the attribute "message"
   */
  public function setWebroot($str) {
    $this->webRoot = $str;
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

    $webRoot = $this->webRoot;
    $uri = $this->activeDomain;

    $activeDomain = str_replace('/', '\/', $uri);
    $domainPlaceholder = 'https:\/\/[--placeholder--]';

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
  }
}

?>