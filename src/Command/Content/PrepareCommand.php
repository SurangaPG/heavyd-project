<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Content;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareCommand extends AbstractHeavydCommandBase {

  /**
   * The source stage to prepare the content for.
   *
   * @var string
   */
  protected $sourceStage;

  /**
   * The source domain for the import.
   *
   * @var string
   */
  protected $domain;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('content:prepare')
      ->setDescription('Prepare the default content files for importing.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);
    $this->sourceStage = $this->askStage();

    $properties = $this->getProperties()->get('host');
    $default = isset($properties['default']['domain']) ? $properties['default']['domain'] : NULL;

    $this->domain = $this->getIo()->ask('Domain for the import', $default);
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskProjectPrepareContent($this->domain, $this->sourceStage);
  }

}