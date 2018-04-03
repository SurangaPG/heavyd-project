<?php
/**
 * @file Contains some useful helpers for common drush commands.
 */
namespace Deployer;

desc('Drush activate maintenance mode');
task('drupal8:drush:maintenance-mode-activate', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:maintenance-mode-activate');
});

desc('Drush deactivate maintenance mode');
task('drupal8:drush:maintenance-mode-deactivate', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:maintenance-mode-deactivate');
});

desc('Drush cache rebuild');
task('drupal8:drush:cache-rebuild', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:cache-rebuild');
});

// Import all the config
desc('Drush config import');
task('drupal8:drush:config-import', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:config-import');
});

// Import update all the entities
desc('Drush entity updates');
task('drupal8:drush:entity-updates', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:entity-updates');
});

// Import update all the entities
desc('Drush database updates');
task('drupal8:drush:update-database', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:update-database');
});

// Run cron
desc('Drush cron');
task('drupal8:drush:cron', function () {
  cd('{{release_path}}');
  run('{{release_path}}/{{bin_phing}} drush:cron');
});

/**
 * Make a fresh install for the drupal site.
 * Depending on what we're doing exactly this can either be the initial set up
 * of a production environment. Or just the building of a test env.
 */
desc('Installs a drupal site, usually this is only done once for production but can be done any number of times.');
task('drupal8:drush:site-install', function () {
  writeLn('Installing drupal');
  cd('{{release_path}}');
  run('chmod -R u+w {{release_path}}/{{dir_web_relative}}');
  run('{{release_path}}/{{bin_phing}} drush:site-install');
});