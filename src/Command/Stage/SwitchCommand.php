<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Stage;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitchCommand extends AbstractHeavydCommandBase {

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('stage:switch')
      ->addArgument('stage', InputArgument::OPTIONAL, 'The machine name of the stage to switch to.')
      ->setDescription('Switch the current file set to match a different stage.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    $stage = $input->getArgument('stage');

    if (!isset($stage)) {
      $stage = $this->askStage();
    }

    $this->stage = $stage;
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getApplication()->getEngine()->setOutput($output);
    $this->getApplication()->getEngine()->taskProjectSwitchStage($this->stage);
  }

}