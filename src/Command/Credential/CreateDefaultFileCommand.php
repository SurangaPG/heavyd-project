<?php

namespace surangapg\Heavyd\Command\Credential;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateDefaultFileCommand extends AbstractHeavydCommandBase {

  /**
   * The level where the config will be saved.
   * @var string
   */
  public $configLevel;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('credential:default-file')
      ->setDescription('Create a default file with some basic information to tweak')
      ->setHelp("All the level27 servers have a .env file which holds their db credentials etc. This command holds an easy way to generate a default.");
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {

    $data = [
      "default" => [
        "default" => [
          "default" => [
            "host" => "localhost",
            "port" => 3306,
            "username" => "db_user",
            "password" => "db_pass",
            "database" => "db_name",
            "driver" => "mysql",
            "namespace" => "Drupal\\Core\\Database\\Driver\\mysql",
          ],
        ],
      ],
    ];

    $dirProperties = $this->getApplication()->getProperties()->get('dir');

    $localFileName = $dirProperties['temp'] . '/.env-default-' . time();
    $fs = new Filesystem();
    $fs->dumpFile($localFileName, json_encode($data, JSON_PRETTY_PRINT));
    $output->writeln('Default version saved in ' . $localFileName);
  }
}