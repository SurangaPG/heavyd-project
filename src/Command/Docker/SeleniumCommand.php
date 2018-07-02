<?php

namespace surangapg\Heavyd\Command\Docker;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use surangapg\HeavydComponents\BinRunner\PhingBinRunner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class SeleniumCommand extends AbstractHeavydCommandBase {

  /**
   * The domain should be without the prefix and any extra slashes etc.
   *
   * For example "baltimore.local"
   *
   * @var string
   */
  protected $domainToBridge;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('docker:selenium')
      ->setDescription('This will start a selenium container that can be used to run various extra commands. Such as behat tests and haunt.');
  }

  /**
   * @inheritdoc
   */
  public function initialize(InputInterface $input, OutputInterface $output) {
    parent::initialize($input, $output);

    $hostProperties = $this->getProperties()->get('host');
    $hostDomain = isset($hostProperties['default']['domain']) ? $hostProperties['default']['domain'] : '';

    // The domain should be without the prefix and any extra slashes etc.
    if (!empty($hostDomain)) {
      $hostDomain = str_replace('https://', '', $hostDomain);
      $hostDomain = str_replace('http://', '', $hostDomain);
      $hostDomain = rtrim($hostDomain, '/');
    }

    // Add the host to bridge.
    $hostDomain = $this->getIo()->ask('Which localhost do you want to bridge? E.g "baltimore.local" (no http prefix or slashes)', $hostDomain);

    // Enfore stripping for silly people that don't read the instructions.
    if (!empty($hostDomain)) {
      $hostDomain = str_replace('https://', '', $hostDomain);
      $hostDomain = str_replace('http://', '', $hostDomain);
      $hostDomain = rtrim($hostDomain, '/');
    }

    $this->setDomainToBridge($hostDomain);
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {

    // Stop container.
    if ($this->checkContainer()) {
      $this->getIo()->newLine();
      $this->getIo()->writeln('<fg=white>Stopping preexisting container:</>');
      $binRunner = new PhingBinRunner(
        '.phing/vendor/bin/phing',
        $output,
        $this->getProperties()->getBasePath()
      );
      $binRunner->addArg('project:selenium-stop');
      $binRunner->run();
    }

    $this->getIo()->newLine();
    $this->getIo()->writeln('<fg=white>Starting bridged container:</>');

    $binRunner = new BinRunner(
      '.phing/vendor/bin/phing',
      $this->getProperties()->getBasePath(),
      $output
    );

    $binRunner->addArg('project:selenium-bridged-start');
    $binRunner->addOption('-Ddomain.to.bridge', $this->getDomainToBridge());
    $binRunner->run();

    // Display the active containers:
    $this->getIo()->newLine();
    $this->getIo()->writeln('<fg=white>Container status: </>');
    passthru('docker ps');
    $this->getIo()->newLine();
  }

  /**
   * @return string
   */
  public function getDomainToBridge() {
    return $this->domainToBridge;
  }

  /**
   * @param $domainToBridge
   */
  public function setDomainToBridge($domainToBridge) {
    $this->domainToBridge = $domainToBridge;
  }

  /**
   * Checks or the container is already running.
   *
   * @throws \Exception
   *   If docker is not active.
   *
   * @return bool
   */
  protected function checkContainer() {

    $projectProperties = $this->getProperties()->get('project');
    $containerName = $projectProperties['group'] . '_' . $projectProperties['machineName'] . '_selenium';

    // If the needed container isn't available.
    $output = [];

    exec('docker ps', $output, $return);

    if ($return != 0) {
      throw new \Exception('Docker deamon is not active. Did you start docker?');
    }

    $found = FALSE;

    foreach ($output as $outputLine) {
      if (strpos($outputLine, $containerName) !== FALSE) {
        $found = TRUE;
        break;
      }
    }

    return $found;
  }

}
