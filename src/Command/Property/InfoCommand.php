<?php

/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Property;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends AbstractHeavydCommandBase {

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('property:info')
      ->setDescription('Print out all the property settings to the command line for reference.');
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $properties = $this->getProperties()->get();

    foreach ($properties as $filePrefix => $data) {
      $this->getIo()->writeln('<fg=yellow>' . $filePrefix . '</>');
      $this->writeAssocToCli($data, '  ');
      $this->getIo()->newLine(1);
    }
  }

  /**
   * @param $data
   * @param $prefix
   */
  protected function writeAssocToCli($data, $prefix) {
    foreach ($data as $key => $subItems) {
      if (is_array($subItems)) {
        $this->getIo()->writeln($prefix . $key . ': ');
        $this->writeAssocToCli($subItems, $prefix . '  ');
      }
      else {
        $this->getIo()->writeln($prefix . $key . ': ' . $subItems);
      }
    }
  }
}