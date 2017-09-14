<?php
/**
 * @file
 * This file contains some extra settings which make development easier and quicker
 * it can be enables via workflow drupal:dev-mode.
 * Warning this file should NOT be active before the site is installed.
 */

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

$settings['container_yamls'][] = __DIR__ . '/services.local.yml';

/**
 * Show all error messages, with backtrace information.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

/**
 * Disable the render cache (this includes the page cache).
 * Do not use this setting until after the site is installed.
 */
$settings['cache']['bins']['render'] = 'cache.backend.null';

/**
 * Disable Dynamic Page Cache.
 */
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

/**
 * Allow test modules and themes to be installed.
 */
$settings['extension_discovery_scan_tests'] = TRUE;

/**
 * Enable access to rebuild.php.
 */
$settings['rebuild_access'] = TRUE;

/**
 * Skip file system permissions hardening.
 */
$settings['skip_permissions_hardening'] = TRUE;