<?php

namespace surangapg\Heavyd\Test\Command\Misc;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\Heavyd\Command\Misc\InstallCommand;

class InstallCommandTest extends TestCase {

  /**
   * Check the basic construction of the file.
   */
  public function testBasicFunctionallity() {
    $installCommand = new InstallCommand();
    Assert::assertInstanceOf('surangapg\Heavyd\Command\AbstractHeavydCommandBase', $installCommand);

    $this->markTestIncomplete('Validate all the functionallity here.');
  }
}
