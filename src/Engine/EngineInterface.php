<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Engine;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface EngineInterface
 *
 * Contains an interface class that should be shared by any engine for the
 * application. This makes it possible to disconnect the actual executors from
 * the underlying items actually running the code.
 *
 * Mainly here because currently I am not convinced about the phing "engine"
 * since robo is now used by drush (which might proof a better option in time).
 *
 * Also useful to display document what the code does/needs.
 *
 * @package surangapg\Application\Engine
 */
interface EngineInterface {

  /**
   * Suppress all the output from the engine.
   *
   * @param bool $toggle
   *  Toggle the output on/off.
   */
  public function setSilent($toggle);

  /**
   * Checks or the engine is silent.
   *
   * @return bool
   *   Is the engine silent.
   */
  public function isSilent();

}

