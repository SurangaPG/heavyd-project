<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Content;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends AbstractHeavydCommandBase {

  /**
   * The target stage to export the content to.
   *
   * @var string
   */
  protected $targetStage;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('content:export')
      ->setDescription('Export the current content to the filesystem.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    $this->targetStage = $this->askStage();
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskProjectExportContent($this->targetStage);
  }

}