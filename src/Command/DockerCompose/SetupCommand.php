<?php

namespace surangapg\Heavyd\Command\DockerCompose;

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
    $this->setName('docker-compose:setup')
      ->setDescription('This sets up a docker compose file ready for use.');
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $this->getEngine()->taskDockerComposeWriteTemplate();
    $this->getIo()->writeln('Generated new docker-compose.yml in the root of the project.');
  }

}
