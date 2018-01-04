<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractHeavydCommandBase extends Command {

  /**
   * @var SymfonyStyle
   *   Symfonfy style helper.
   */
  protected $io;

  /**
   * Get the application.
   *
   * @return \surangapg\Heavyd\HeavydApplication
   *   The application.
   */
  function getApplication() {
    return parent::getApplication();
  }

  /**
   * Get the properties currently loaded into the application.
   *
   * @return \surangapg\Heavyd\Components\Properties\PropertiesInterface
   *   The properties currently loaded into the application.
   */
  function getProperties() {
    return $this->getApplication()->getProperties();
  }

  /**
   * Get the engine for the application.
   *
   * @return \surangapg\Heavyd\Engine\EngineInterface
   *   The engine for the application.
   */
  function getEngine() {
    return $this->getApplication()->getEngine();
  }

  /**
   * Rebuilds the properties currently loaded into the application.
   */
  function rebuildProperties() {
    return $this->getApplication()->rebuildProperties();
  }

  /**
   * Outputs the current state to the cli.
   */
  function outputCurrentState() {
    $this->getApplication()->outputCurrentState($this->getIo());
  }

  /**
   * @inheritdoc
   */
  public function initialize(InputInterface $input, OutputInterface $output) {
    $this->setIo(new SymfonyStyle($input, $output));
    parent::initialize($input, $output);
  }

  /**
   * @return \Symfony\Component\Console\Style\SymfonyStyle
   */
  public function getIo() {
    return $this->io;
  }

  /**
   * @param \Symfony\Component\Console\Style\SymfonyStyle $io
   */
  public function setIo(SymfonyStyle $io) {
    $this->io = $io;
  }

  /**
   * Ask for a valid site machine name.
   *
   * @param array $filters
   *   Any sites not to supply as options.
   * @param bool $defaultToActive|TRUE
   *   Use the currently active item as default.
   *
   * @return string
   *   Env machine name.
   */
  public function askSite($filters = [], $defaultToActive = TRUE) {

    $projectProperties = $this->getProperties()->get();

    $default = NULL;
    if ($defaultToActive && isset($projectProperties['project']['active']['site'])) {
      $default = $projectProperties['project']['active']['site'];
    }

    $sites = glob($projectProperties['dir']['etc']['site'] . '/*', GLOB_ONLYDIR);
    $options = [];
    foreach ($sites as $site) {
      $baseName = basename($site);
      if (!in_array($baseName, $filters)) {
        $options[$baseName] = $baseName;
      }
    }

    return $this->getIo()->choice('Which site are you using', $options, $default);
  }

  /**
   * Ask for a valid stage machine name.
   *
   * @param array $filters
   *   Any stages not to supply as options.
   * @param bool $defaultToActive|TRUE
   *   Use the currently active item as default.
   *
   * @return string
   *   Env machine name.
   */
  public function askStage($filters = ['install'], $defaultToActive = TRUE) {

    $projectProperties = $this->getProperties()->get();

    $default = NULL;
    if ($defaultToActive && isset($projectProperties['project']['active']['stage'])) {
      $default = $projectProperties['project']['active']['stage'];
    }

    $stages = glob($projectProperties['dir']['etc']['stage'] . '/*', GLOB_ONLYDIR);
    $options = [];
    foreach ($stages as $stage) {
      $baseName = basename($stage);
      if (!in_array($baseName, $filters)) {
        $options[$baseName] = $baseName;
      }
    }

    return $this->getIo()->choice('Which stage are you using', $options, $default);
  }

  /**
   * Ask for a valid env machine name.
   *
   * @param array $filters
   *   Any environments not to supply as options.
   * @param bool $defaultToActive|TRUE
   *   Use the currently active item as default.
   *
   * @return string
   *   Env machine name.
   */
  public function askEnv($filters = [], $defaultToActive = TRUE) {

    $projectProperties = $this->getProperties()->get();

    $default = NULL;
    if ($defaultToActive && isset($projectProperties['project']['active']['stage'])) {
      $default = $projectProperties['project']['active']['env'];
    }

    $envs = glob($projectProperties['dir']['etc']['env'] . '/*', GLOB_ONLYDIR);
    $options = [];
    foreach ($envs as $env) {
      $baseName = basename($env);
      if (!in_array($baseName, $filters)) {
        $options[$baseName] = $baseName;
      }
    }

    return $this->getIo()->choice('Which environment are you using', $options, $default);
  }
}