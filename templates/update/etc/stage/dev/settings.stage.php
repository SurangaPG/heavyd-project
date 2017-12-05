<?php
/**
 * @file
 * This file contains some extra settings which make development easier and quicker
 * it can be enables via workflow drupal:dev-mode.
 * Warning this file should NOT be active before the site is installed.
 */

// Settings to enable the dev config split.

// Use development config in dev environments.
$config['config_split.config_split.dev']['status'] = TRUE;

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