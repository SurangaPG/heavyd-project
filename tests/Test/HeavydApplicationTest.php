<?php

namespace surangapg\Heavyd\Test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\Heavyd\HeavydApplication;

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

    $app = HeavydApplication::create($sampleProjectRoot);

    // Check or all the helpers were added correctly.
    Assert::assertInstanceOf('surangapg\Heavyd\HeavydApplication', $app);
    Assert::assertInstanceOf('surangapg\Heavyd\Engine\EngineInterface', $app->getEngine());
    Assert::assertInstanceOf('surangapg\HeavydComponents\Properties\PropertiesInterface', $app->getProperties());

  }

  /**
   * Test the creating of a new HeavyD project application.
   *
   * @covers HeavydApplication::create
   */
  public function testCreateInRoot() {

    $sampleProjectRoot = $this->getFixtureDir() . '/full-project';

    $app = HeavydApplication::create($sampleProjectRoot);

    // Check that the project base path was detected correctly
    Assert::assertEquals($this->getFixtureDir() . '/full-project', $app->getBasePath());

  }

  /**
   * Test the creating of a new HeavyD project application.
   *
   * @covers HeavydApplication::create
   */
  public function testCreateInTree() {

    $sampleProjectRoot = $this->getFixtureDir() . '/full-project/web/sites';

    $app = HeavydApplication::create($sampleProjectRoot);

    // Check that the project base path was detected correctly
    Assert::assertEquals($this->getFixtureDir() . '/full-project', $app->getBasePath());

  }

  /**
   * Test that starting an application outside the tree of a valid project.
   *
   * @expectedException \Exception
   *
   * @covers HeavydApplication::create
   */
  public function testCreateOutsideTree() {
    $app = HeavydApplication::create($this->getFixtureDir());
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