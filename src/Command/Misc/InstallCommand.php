<?php

/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Misc;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class InstallCommand extends AbstractHeavydCommandBase {

  /**
   * Stage to install.
   *
   * @var string
   */
  public $targetStage;

  /**
   * Env to install.
   *
   * @var string
   */
  public $targetEnv;

  /**
   * Site to install.
   *
   * @var string
   */
  public $targetSite;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('install')
      ->setDescription('Installs a local project.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    // @TODO add none interactive support
    if(!$input->isInteractive()) {
      $this->getIo()->error('Can\'t run this non interactively');
      exit;
    }

    $this->getIo()->warning('This will completely reinstall the current site.');
    $continue = $this->getIo()->confirm('Continue?');

    if (!$continue) {
      $this->getIo()->writeln('Aborted');
      $this->getIo()->newLine();
      exit;
    }

    $this->targetStage = $this->askStage();
    $this->targetEnv = $this->askEnv();
    $this->targetSite = $this->askSite();
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskProjectInstall($this->targetEnv, $this->targetStage, $this->targetSite);
    $this->getIo()->success('Setup Completed');
  }

}