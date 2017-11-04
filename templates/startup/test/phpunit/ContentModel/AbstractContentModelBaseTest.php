<?php

namespace DrupalProject\Tests\ContentModel;

use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractContentModel
 *
 * Basic test to validate the different content models.
 *
 * @package DrupalProject\Tests
 */
abstract class AbstractContentModelBaseTest extends \PHPUnit_Framework_TestCase {

  /**
   * All the data to validate.
   *
   * Should be a list of field information,
   *
   * @var array
   */
  protected $bundleData = [];

  /**
   * Check or all the types for the entity exist.
   */
  public function testBundleExistence() {
    foreach ($this->bundleData as $bundleName => $bundleExpectation) {
      $bundleFile = sprintf('%s.type.%s.yml',
        $this->provideEntityType(),
        $bundleName
      );

      $this->assertFileExists(
        $this->configDir() . $bundleFile,
        sprintf('%s type: "%s" does not exist',
          $this->provideEntityType(),
          $bundleName
        )
      );

      $bundleData = Yaml::parse(file_get_contents($this->configDir() . $bundleFile));
      // Check or the label is correct.
      $this->assertEquals(
        $bundleExpectation['label'],
        $bundleData['name'],
        sprintf(
          'Label for "%s:%s does not match the expectation',
          $this->provideEntityType(),
          $bundleName
        )
      );
    }
  }

  /**
   * Checks or all the fields have been connected to the needed bundle.
   */
  public function testConnectedFields() {
    foreach ($this->bundleData as $bundleName => $bundleData) {
      foreach ($bundleData['fields'] as $fieldName => $fieldExpectations ) {
        $fieldFile = sprintf('field.field.%s.%s.%s.yml', $this->provideEntityType(), $bundleName, $fieldName);

        $this->assertFileExists(
          $this->configDir() . $fieldFile,
          sprintf('field "%s" for "%s:%s" does not exist',
            $fieldName,
            $this->provideEntityType(),
            $bundleName
          )
        );

        $fieldData = Yaml::parse(file_get_contents($this->configDir() . $fieldFile));

        // Check the type of the field.
        $this->assertEquals(
          $fieldExpectations['type'],
          $fieldData['field_type'],
          sprintf('field "%s" for "%s:%s" is the wrong type. Expected "%s" got "%s".',
            $fieldName,
            $this->provideEntityType(),
            $bundleName,
            $fieldExpectations['type'],
            $fieldData['field_type']
          )
        );
      }
    }
  }

  /**
   * Root directory for the project.
   *
   * @return string
   */
  public function projectRootDir() {
    return dirname(dirname(dirname(__DIR__))) . '/';
  }

  /**
   * Location where all the config files are exported to.
   *
   * @return string
   */
  public function configDir() {
    return $this->projectRootDir() . 'etc/site/default/config/';
  }

  /**
   * Location where the data model source files are situated.
   *
   * @return string
   */
  public function dataModelDir() {
    return dirname(__DIR__) . '/data-model/';
  }

  /**
   * AbstractContentModelBaseTest constructor.
   * @param null $name
   * @param array $data
   * @param string $dataName
   */
  public function __construct($name = null, array $data = array(), $dataName = '') {
    parent::__construct($name, $data, $dataName);
    $this->provideSourceData();
  }

  /**
   * The type of entity to handle.
   *
   * @return string
   */
  abstract public function provideEntityType();

  /**
   * Get all the different pieces of data needed to generate the test.
   *
   * @return array
   *   Should be an array with the following keys.
   */
  public function provideSourceData() {
    $bundleFiles = glob($this->dataModelDir() . $this->provideEntityType() .'/*.yml');
    foreach ($bundleFiles as $bundleFile) {
      $bundleMachineName = str_replace('.yml', '', basename($bundleFile));
      $this->bundleData[$bundleMachineName] = Yaml::parse(file_get_contents($bundleFile));
    }
  }
}
