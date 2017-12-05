<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Engine;

use surangapg\Heavyd\Components\BinRunner\BinRunner;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class PhingEngine implements EngineInterface {

  /**
   * Absolute path to where the engine can be run from.
   *
   * @var string
   */
  protected $projectPath;

  /**
   * The output interface for the item.
   *
   * @var OutputInterface
   */
  protected $output;

  /**
   * PhingEngine constructor.
   *
   * @param string $projectPath
   *   The path for the project.
   * @param \Symfony\Component\Console\Output\OutputInterface|NULL $output
   *   Output interface.
   */
  public function __construct(string $projectPath, OutputInterface $output = NULL) {
    $this->projectPath = $projectPath;

    if (!isset($output)) {
      $output = new BufferedOutput();
    }
    $this->output = $output;
  }

  /**
   * Make the entire filesystem writable.
   */
  public function taskProjectUnlock() {
    echo __FUNCTION__;
  }

  /**
   * Install a site.
   *
   * This should run all the needed steps to fully (re-install) the site.
   * Ending in a clean full site install for the correct stage/env/site.
   */
  public function taskProjectInstall() {
    echo __FUNCTION__;
  }

  /**
   * Install all the needed dependencies for the project. Gets all the yarn,
   * npm, composer etc.
   */
  public function taskProjectInstallDependencies() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskEnvSwitch(string $envMachineName) {
    $binRunner = new BinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:activate-env');
    $binRunner->addOption('-Denv.to.activate', $envMachineName);
    $binRunner->run();
  }

  /**
   * {@inheritdoc}
   */
  public function taskEnvStartServices() {
    echo __FUNCTION__;
  }
  /**
   * {@inheritdoc}
   */
  public function taskEnvStopServices() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskStageSwitch(string $stageMachineName) {
    $binRunner = new BinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:activate-stage');
    $binRunner->addOption('-Dstage.to.activate', $stageMachineName);
    $binRunner->run();
  }

  /**
   * {@inheritdoc}
   */
  public function taskStageSetupContent() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskStageExportContent() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskImportLocale() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskExportLocale() {
    echo __FUNCTION__;
  }

  /**
   * {@inheritdoc}
   */
  public function taskSiteSwitch(string $siteMachineName) {
    echo __FUNCTION__;
  }

  /**
   * Clean all the local assets.
   *
   * Empties out all the files in the temp directory for the site.
   */
  public function taskAssetsClean() {
    echo __FUNCTION__;
  }

  /**
   * Proxy all the local assets.
   */
  public function taskAssetsProxy() {
    echo __FUNCTION__;
  }

  /**
   * @return \Symfony\Component\Console\Output\OutputInterface
   */
  public function getOutput() {
    return $this->output;
  }

  /**
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   */
  public function setOutput(OutputInterface $output) {
    $this->output = $output;
  }
}

