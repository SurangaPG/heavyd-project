<?php
/*
 * Get a full Drupal 8 build process.
 */

namespace Deployer;

use Deployer\Task\Context; // This is loaded in by the deployer.phar and is accessible

# Dependencies from the standard deployer phar
require 'recipe/common.php';

# Extra task requirements
require 'recipes/tar.php';
require 'recipes/drupal8.php';
require 'recipes/cleanup.php';
require 'recipes/build.php';

set('tar_ignore', [
  '.workflow',
  '.git',
  '.platform',
  'phpcs-rulset.xml.dist',
  'phpunit.xml.dist',
  '.travis.yml',
  '.workflow.yml',
  '.bitbucket-pipelines.yml',
  'temp',
  'tests',
  'patches',
  'command',
  'artifact',
  '.deployer',
]);

/*
 * Contains the full deploy runs.
 */
desc('Run a full deploy from start to end. Building all the code on the build server and tarring it afterwards');
task('deploy:full', [
  'step:prepare',
  'step:build',
  'step:sync',
  'step:deploy',
  'step:cleanup',
]);

desc('Run a full deploy that only alters the code');
task('deploy:code', [
  'step:prepare',
  'step:build',
  'step:sync',
  'step:cleanup',
]);

desc('Run a full deploy that installs the site for the first time');
task('deploy:install', [
  'step:prepare',
  'step:build',
  'step:sync',
  'drupal8:drush:site-install',
  'step:cleanup',
]);

/*
 * Contains the various smaller steps for the deploy process.
 * Note that these are NOT always stand alone. Some do rely on other steps
 * in the process. e.g the step:sync is reliant on the prepare step.
 */
desc('Run the prepare step. This counts the releases, locks the various folders
etc. Should be run at the start of any deploy that will use the deployer folder
structure.');
task('step:prepare', [
  'deploy:prepare', // Prepares some symlinks etc
  'deploy:lock', // Locks this deploy (prevents several deploys running together)
  'deploy:release', // Count the release
]);

desc('Run the build step which prepares the entire codebase. ');
task('step:build', [
  'build:dependencies', // Prepares some symlinks etc
  'build:activate-env', // Locks this deploy (prevents several deploys running together)
  'build:activate-stage', // Count the release
  'build:rebase-project', // Rebases the properties based on the final basepath (accounts for the difference in full path between the eventual build location and the local build location)
]);

desc('Synchronize the current codebase to the remote server.');
task('step:sync', [
  'tar:archive',
  'tar:sftp',
  'tar:un-archive',
  'tar:cleanup',
  'deploy:shared',
]);

desc('Run a full deploy from start to end. Building all the code on the build server and tarring it afterwards');
task('step:deploy', [
  'drupal8:drush:cache-rebuild',
  'drupal8:drush:config-import',
  'drupal8:drush:entity-updates',
  'drupal8:drush:update-database',
  'drupal8:drush:cron',
]);

desc('Step used to finish up the process. Removes the lock, cleans up the old releases etc');
task('step:cleanup', [
  'deploy:symlink',
  'deploy:unlock',
  'cleanup:unlock', // @TODO define this a as a before task.
  'cleanup'
]);
