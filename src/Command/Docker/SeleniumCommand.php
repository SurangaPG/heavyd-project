<?php

namespace surangapg\Heavyd\Command\Docker;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use surangapg\Heavyd\Command\Base\DockerCommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class SeleniumCommand extends AbstractHeavydCommandBase {

  use DockerCommandTrait;

  /**
   * The domain should be without the prefix and any extra slashes etc.
   *
   * For example "baltimore.local"
   *
   * @var string
   */
  protected $domainToBridge;

  /**
   * The browser to use in the selenium container.
   *
   * @var string
   */
  protected $browser = 'firefox';

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('docker:selenium')
      ->addOption('browser', NULL, InputOption::VALUE_REQUIRED, 'firefox')
      ->setDescription('This will start a selenium container that can be used to run haunt commands.');
  }

  public function initialize(InputInterface $input, OutputInterface $output) {
    parent::initialize($input, $output);

    if (!empty($input->getOption('browser'))) {
      $this->browser = $input->getOption('browser');
    }
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

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
    if ($this->checkContainer('selenium')) {
      $this->getIo()->writeln('<fg=white>Stopping existing container:</>');
      $this->getEngine()->taskProjectSeleniumStop();
    }

    $this->getIo()->newLine();
    $this->getIo()->writeln('<fg=white>Starting bridged container:</>');
    $this->getEngine()->taskProjectSeleniumStart($this->domainToBridge, $this->browser, TRUE);

    // Display the active containers:
    $this->getIo()->newLine();
    $this->showContainerStatus();
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

}
