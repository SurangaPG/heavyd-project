<?php

/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Property;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RewriteCommand extends AbstractHeavydCommandBase {

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('property:rewrite')
      ->setDescription('Rewrite all the property files.');
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getIo()->title('Regenerating property files');

    $this->outputCurrentState();

    $this->getIo()->writeln('<fg=yellow>Rebuilding via engine</>');
    $this->getEngine()->taskProjectWriteProperties();
    $this->getIo()->writeln('<fg=green>Done</>');
    $this->getIo()->newLine();

    $this->getIo()->writeln('Generated new property files for project.');
    $this->getIo()->newLine();
  }
}