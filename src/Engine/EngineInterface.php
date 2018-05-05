<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Engine;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface EngineInterface
 *
 * Contains an interface class that should be shared by any engine for the
 * application. This makes it possible to disconnect the actual executors from
 * the underlying items actually running the code.
 *
 * Mainly here because currently I am not convinced about the phing "engine"
 * since robo is now used by drush (which might proof a better option in time).
 *
 * Also useful to display document what the code does/needs.
 */
interface EngineInterface {

  /**
   * Suppress all the output from the engine.
   *
   * @param bool $toggle
   *  Toggle the output on/off.
   */
  public function setSilent($toggle);

  /**
   * Checks or the engine is silent.
   *
   * @return bool
   *   Is the engine silent.
   */
  public function isSilent();

  /**
   * Make the entire filesystem writable.
   */
  public function taskProjectUnlock();

  /**
   * Install a site.
   *
   * This should run all the needed steps to fully (re-install) the site.
   * Ending in a clean full site install for the correct stage/env/site.
   *
   * @param string $envMachineName
   *   Machine name for the env being activated.
   * @param string $stageMachineName
   *   Machine name for the stage being activated.
   * @param string $siteMachineName
   *   Machine name for the site being activated.
   *
   * @throws \surangapg\HeavydComponents\BinRunner\Exception\BinRunFailedException
   *   When the phing fails to install.
   */
  public function taskProjectInstall(string $envMachineName, string $stageMachineName, string $siteMachineName);

  /**
   * Switch the environment.
   *
   * Environments are the information about the actual machine you are on.
   * It should move any files needed from the /etc folders to the actual project
   * build. Allowing for a simple way to always have the correct connections
   * to the database etc.
   *
   * Conceptually speaking typical ideas for environments are:
   *  - docker
   *  - mamp
   *  - level27
   *  - platform
   *  - vagrant
   * etc.
   *
   * As a rule the environment should only really contain data that is about
   * how it connects to extra services etc for the host/server the build is on.
   *
   * @param string $envMachineName
   *   The machine name for the "environment" to activate.
   */
  public function taskProjectSwitchEnv(string $envMachineName);

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
  public function taskProjectSwitchStage(string $stageMachineName);

  /**
   * Switch the site.
   *
   * Sites are all the typical site as provided by a drupal core multisite
   * instance.
   *
   * Conceptually speaking typical ideas for the site are:
   *  - default
   * Corresponds with a drupal web/sites/ subdir name.
   *
   * As a rule a stage should not contain any credentials etc if it can be
   * avoided.
   *
   * @param string $siteMachineName
   *   The machine name for the "site" to activate.
   */
  public function taskProjectSwitchSite(string $siteMachineName);

  /**
   * Resets the current installation to the basic settings.
   *
   * This forces a re-install without updating any files etc.
   * Useful for resetting test environments after they have been build.
   */
  public function taskProjectResetInstall();

  /**
   * Export all the default content.
   *
   * @param string $targetStage
   *   Stage to export the current content to, e.g dev, acc, prod etc.
   */
  public function taskProjectExportContent(string $targetStage);

  /**
   * Import all the staged default content.
   */
  public function taskProjectImportContent();

  /**
   * Write all the properties for the project.
   */
  public function taskProjectWriteProperties();

  /**
   * @return \Symfony\Component\Console\Output\OutputInterface
   */
  public function getOutput();

  /**
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   */
  public function setOutput(OutputInterface $output);

}

