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
   * Start any needed extra local service.
   *
   * This allows items such as solr etc to be started up based on the settings
   * in the codebase. As required by the environment.
   */
  public function taskEnvStartServices() {
    echo __FUNCTION__;
  }
  /**
   * Stop any services needed locally.
   *
   * This allows the stopping of any needed services on the local machine.
   * As required by the environment.
   */
  public function taskEnvStopServices() {
    echo __FUNCTION__;
  }

  /**
   * Switch the stage.
   *
   * Stages are the typical phases of a development cycle. They usually handle
   * the cache, debug settings etc. A typical example would be the dtap cycle.
   * It should move any files needed from the /etc folders to the actual project
   * build.
   *
   * Conceptually speaking typical ideas for stage are:
   *  - dev
   *  - test
   *  - acc
   *  - prod
   *  - install
   * etc.
   *
   * As a rule a stage should not contain any credentials etc if it can be
   * avoided.
   *
   * @param string $stageMachineName
   *   The machine name for the "stage" to activate.
   */
  public function taskStageSwitch(string $stageMachineName) {
    echo __FUNCTION__;
  }

  /**
   * Import all the default content.
   */
  public function taskStageSetupContent() {
    echo __FUNCTION__;
  }

  /**
   * Export all the default content.
   */
  public function taskStageExportContent() {
    echo __FUNCTION__;
  }

  /**
   * Import all the locale data.
   */
  public function taskImportLocale() {
    echo __FUNCTION__;
  }

  /**
   * Export all the locale data.
   */
  public function taskExportLocale() {
    echo __FUNCTION__;
  }

  /**
   * Switch the site.
   *
   * Sites are the typical items that are based on a drupal multisite idea. They
   * contain all the settings etc that define a single site. E.g the drupal
   * config, locale data, asset storage locations etc.
   * It should move any files needed from the /etc folders to the actual project
   * build.
   *
   * Conceptually speaking typical ideas for site are the things that are in
   * your sites folders.
   *
   * @param string $siteMachineName
   *   The machine name for the "site" to activate. Should match the name of
   *   the directory in the sites folder.
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

}

