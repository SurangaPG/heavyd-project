<?php

namespace surangapg\Heavyd\Command\Docker;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use surangapg\Heavyd\Command\Base\DockerCommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class SetupCommand extends AbstractHeavydCommandBase {

  use DockerCommandTrait;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('setup')
      ->setDescription('This will start a the underlying docker network.');
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskDockerComposeWriteTemplate();
  }

}
