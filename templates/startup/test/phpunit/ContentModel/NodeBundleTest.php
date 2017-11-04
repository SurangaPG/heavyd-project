<?php

namespace DrupalProject\Tests\ContentModel;

/**
 * Class AbstractContentModel
 *
 * Basic test to validate the different content models.
 *
 * @package DrupalProject\Tests
 */
class NodeBundleTest extends AbstractContentModelBaseTest {

  /**
   * @inheritdoc
   */
  public function provideEntityType() {
    return 'node';
  }

}