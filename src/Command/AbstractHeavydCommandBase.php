<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractHeavydCommandBase extends Command {

  /**
   * @var SymfonyStyle
   *   Symfonfy style helper.
   */
  protected $io;

  /**
   * Get the application.
   *
   * @return \surangapg\Heavyd\HeavydApplication
   *   The application.
   */
  function getApplication() {
    return parent::getApplication();
  }

  /**
   * Get the properties currently loaded into the application.
   *
   * @return \surangapg\Heavyd\Components\Properties\PropertiesInterface
   *   The properties currently loaded into the application.
   */
  function getProperties() {
    return $this->getApplication()->getProperties();
  }

  /**
   * Get the engine for the application.
   *
   * @return \surangapg\Heavyd\Engine\EngineInterface
   *   The engine for the application.
   */
  function getEngine() {
    return $this->getApplication()->getEngine();
  }

  /**
   * Rebuilds the properties currently loaded into the application.
   */
  function rebuildProperties() {
    return $this->getApplication()->rebuildProperties();
  }

  /**
   * Outputs the current state to the cli.
   */
  function outputCurrentState() {
    $this->getApplication()->outputCurrentState($this->getIo());
  }

  /**
   * @inheritdoc
   */
  public function initialize(InputInterface $input, OutputInterface $output) {
    $this->setIo(new SymfonyStyle($input, $output));
    parent::initialize($input, $output);
  }

  /**
   * @return \Symfony\Component\Console\Style\SymfonyStyle
   */
  public function getIo() {
    return $this->io;
  }

  /**
   * @param \Symfony\Component\Console\Style\SymfonyStyle $io
   */
  public function setIo(SymfonyStyle $io) {
    $this->io = $io;
  }
}