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

class ResetCommand extends AbstractHeavydCommandBase {

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
    $this->setName('reset')
      ->setDescription('Reset the current installation to the current set without file system changes.');
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

    $this->getIo()->warning([
      "This will completely reinstall the current site. \n - The site will be re-installed (via drush)",
    ]);
    $continue = $this->getIo()->confirm('Continue?');

    if (!$continue) {
      $this->getIo()->writeln('Aborted');
      $this->getIo()->newLine();
      exit;
    }
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskProjectResetInstall();
    $this->getIo()->success('Reset Completed');
  }

}