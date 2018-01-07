<?php
/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Base;

use surangapg\Heavyd\Components\Properties\PropertiesInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

trait DockerCommandTrait {

  /**
   * Checks or the container is already running.
   *
   * @param string $type
   *   The type of container (in essence it's suffix. )
   * @param bool $onlyCheckRunningContainers
   *   Only check the currently running containers.
   *
   * @throws \Exception
   *   If docker is not active.
   *
   * @return bool
   */
  protected function checkContainer(string $type, $onlyCheckRunningContainers = TRUE) {

    $projectProperties = $this->getProperties()->get('project');
    $containerName = $projectProperties['group'] . '_' . $projectProperties['machineName'] . '_' . $type;

    // If the needed container isn't available.
    $output = [];

    if ($onlyCheckRunningContainers) {
      exec('docker ps', $output, $return);
    }
    else {
      exec('docker ps -a', $output, $return);
    }

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

  /**
   * Show the status of the container.
   */
  protected function showContainerStatus() {
    $this->getIo()->writeln('<fg=white>Container status: </>');
    passthru('docker ps');
    $this->getIo()->newLine();
  }

  /**
   * @return PropertiesInterface
   */
  abstract function getProperties();

  /**
   * @return SymfonyStyle;
   */
  abstract function getIo();
}
