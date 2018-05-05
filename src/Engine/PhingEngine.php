<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Engine;

use surangapg\HeavydComponents\BinRunner\PhingBinRunner;
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
   * Forces all output to be hidden from the cli.
   *
   * @var bool
   *   Is the engine output suppressed.
   */
  protected $silent = FALSE;

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
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:unlock');
    $binRunner->run(!$this->isSilent());
  }

  /**
   * Install a site.
   *
   * This should run all the needed steps to fully (re-install) the site.
   * Ending in a clean full site install for the correct stage/env/site.
   *
   * {@inheritdoc}
   */
  public function taskProjectInstall(string $envMachineName, string $stageMachineName, string $siteMachineName) {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:install');
    $binRunner->addOption('-Dfinal.env', $envMachineName);
    $binRunner->addOption('-Dfinal.stage', $stageMachineName);
    $binRunner->addOption('-Dfinal.site', $siteMachineName);
    $binRunner->run(!$this->isSilent());
  }

  /**
   * {@inheritdoc}
   */
  public function taskProjectSwitchEnv(string $envMachineName) {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:activate-env');
    $binRunner->addOption('-Denv.to.activate', $envMachineName);
    $binRunner->run(!$this->isSilent());
  }

  /**
   * {@inheritdoc}
   */
  public function taskProjectSwitchStage(string $stageMachineName) {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:activate-stage');
    $binRunner->addOption('-Dstage.to.activate', $stageMachineName);
    $binRunner->run(!$this->isSilent());
  }

  /**
   * {@inheritdoc}
   */
  public function taskProjectSwitchSite(string $siteMachineName) {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:activate-site');
    $binRunner->addOption('-Dsite.to.activate', $siteMachineName);
    $binRunner->run(!$this->isSilent());
  }

  /**
   * {@inheritdoc}
   */
  public function taskProjectResetInstall() {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:reset-install');
    $binRunner->run(!$this->isSilent());
  }

  /**
   * Export all the default content.
   *
   * @inheritdoc
   */
  public function taskProjectExportContent(string $targetStage) {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:export-content');
    $binRunner->addOption('-Dactive.to.stage', $targetStage);
    $binRunner->run(!$this->isSilent());
  }

  /**
   * Import all the staged default content.
   */
  public function taskProjectImportContent() {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:import-content');
    $binRunner->run(!$this->isSilent());
  }

  /**
   * Make the entire filesystem writable.
   */
  public function taskProjectWriteProperties() {
    $binRunner = new PhingBinRunner('.heavyd/vendor/bin/phing', $this->projectPath, $this->output);
    $binRunner->addArg('project:write-property-files');
    $binRunner->run(!$this->isSilent());
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

  /**
   * Suppress all the output from the engine.
   *
   * @param bool $toggle
   *  Toggle the output on/off.
   */
  public function setSilent($toggle) {
    $this->silent = $toggle;
  }

  /**
   * Checks or the engine is silent.
   *
   * @return bool
   *   Is the engine silent.
   */
  public function isSilent() {
    return $this->silent;
  }

}

