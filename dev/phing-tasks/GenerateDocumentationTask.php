<?php

require_once "phing/Task.php";

/**
 * Class GenerateDocumentationTask
 *
 * Generate all the documentation for the various phing targets and
 * write them to html files.
 */
class GenerateDocumentationTask extends Task {

  /**
   * @var string
   *   Main directory to place the output.
   */
  private $outputDir;

  /**
   * @var string
   *   The main task file for the project.
   */
  private $mainTaskFile;

  /**
   * @var string
   *   The dir with all the phing subtasks.
   */
  private $subTaskDir;

  /**
   * @var string
   *   The dir with all the partial html files.
   */
  private $htmlPartialsDir;

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


  }

  /**
   * @return string
   */
  public function getHtmlPartialsDir() {
    return $this->htmlPartialsDir;
  }

  /**
   * @return string
   */
  public function getMainTaskFile() {
    return $this->mainTaskFile;
  }

  /**
   * @return string
   */
  public function getOutputDir() {
    return $this->outputDir;
  }

  /**
   * @return string
   */
  public function getSubTaskDir() {
    return $this->subTaskDir;
  }

  /**
   * @param $htmlPartialsDir
   */
  public function setHtmlPartialsDir($htmlPartialsDir) {
    $this->htmlPartialsDir = $htmlPartialsDir;
  }

  /**
   * @param $mainTaskFile
   */
  public function setMainTaskFile($mainTaskFile) {
    $this->mainTaskFile = $mainTaskFile;
  }

  /**
   * @param $outputDir
   */
  public function setOutputDir($outputDir) {
    $this->outputDir = $outputDir;
  }

  /**
   * @param $subTaskDir
   */
  public function setSubTaskDir($subTaskDir) {
    $this->subTaskDir = $subTaskDir;
  }
}

?>