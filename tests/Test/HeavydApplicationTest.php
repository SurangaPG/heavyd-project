<?php

namespace surangapg\Heavyd\Test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\Heavyd\HeavydApplication;
use surangapg\HeavydComponents\Scope\Scope;

class HeavydApplicationTest extends TestCase {

  /**
   * Test the creating of a new HeavyD project application.
   *
   * Checks that the properties, engine and main application are created
   * correctly.
   *
   * @covers HeavydApplication::create
   */
  public function testCreate() {
    $sampleProjectRoot = $this->getFixtureDir() . '/full-project';

    $scope = new Scope($sampleProjectRoot);
    $app = HeavydApplication::create(['project' => $scope]);

    // Check or all the helpers were added correctly.
    Assert::assertInstanceOf('surangapg\Heavyd\HeavydApplication', $app);
    Assert::assertInstanceOf('surangapg\Heavyd\Engine\EngineInterface', $app->getEngine());
    Assert::assertInstanceOf('surangapg\HeavydComponents\Properties\PropertiesInterface', $app->getProperties());

  }

  /**
   * If no properties were found the application should prompt the user before
   * running any command.
   *
   * @see HeavydApplication::ensureProperties()
   */
  public function testAutocreatedProperties() {
    $this->markTestIncomplete('Ensure that properties are auto generated when running a command in an environment were no property files could be found.');
  }

  /**
   * Get the location for the fixtures directory.
   *
   * @return string
   *   Absolute location for all the fixtures.
   */
  protected function getFixtureDir() {
    return dirname(__DIR__) . '/fixtures';
  }
}