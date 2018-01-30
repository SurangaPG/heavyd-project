<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Env;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitchCommand extends AbstractHeavydCommandBase {

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('env:switch')
      ->addArgument('environment', InputArgument::OPTIONAL, 'The machine name of the environment to switch to.')
      ->setDescription('Switch the current file set to match a different environment.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    $environment = $input->getArgument('environment');

    if (!isset($environment)) {
      $environment = $this->askEnv();
    }

    $environment->environment = $environment;
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskProjectSwitchEnv($this->environment);
  }

}