<?php

namespace surangapg\Heavyd\Test\Command\Misc;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\Heavyd\Command\Misc\SetupCommand;

class SetupCommandTest extends TestCase {

  /**
   * Check the basic construction of the file.
   */
  public function testBasicFunctionallity() {
    $installCommand = new SetupCommand();
    Assert::assertInstanceOf('surangapg\Heavyd\Command\AbstractHeavydCommandBase', $installCommand);

    $this->markTestIncomplete('Validate all the functionallity here.');
  }
}
