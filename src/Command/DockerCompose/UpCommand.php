<?php

namespace surangapg\Heavyd\Command\DockerCompose;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use surangapg\Heavyd\Command\Base\DockerCommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class UpCommand extends AbstractHeavydCommandBase {

  use DockerCommandTrait;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('docker-compose:up')
      ->setDescription('Start a the underlying docker network.');
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskDockerComposeUp();
  }

}
