<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Engine;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class PhingEngine implements EngineInterface {

  /**
   * Absolute path to where the engine can be run from.
   *
   * @var string
   */
  protected $projectPath;

  /**
   * The output interface for the item.
   *
   * @var OutputInterface
   */
  protected $output;

  /**
   * Forces all output to be hidden from the cli.
   *
   * @var bool
   *   Is the engine output suppressed.
   */
  protected $silent = FALSE;

  /**
   * PhingEngine constructor.
   *
   * @param string $projectPath
   *   The path for the project.
   * @param \Symfony\Component\Console\Output\OutputInterface|NULL $output
   *   Output interface.
   */
  public function __construct(string $projectPath, OutputInterface $output = NULL) {
    $this->projectPath = $projectPath;

    if (!isset($output)) {
      $output = new BufferedOutput();
    }
    $this->output = $output;
  }

  /**
   * Suppress all the output from the engine.
   *
   * @param bool $toggle
   *  Toggle the output on/off.
   */
  public function setSilent($toggle) {
    $this->silent = $toggle;
  }

  /**
   * Checks or the engine is silent.
   *
   * @return bool
   *   Is the engine silent.
   */
  public function isSilent() {
    return $this->silent;
  }

}

