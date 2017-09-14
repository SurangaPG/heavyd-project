<?php
/**
 * @file Contains some useful helpers for common drush commands.
 */
namespace Deployer;

set('drush7_bin', 'drush');

desc('Drush activate maintenance mode');
task('drupal7:drush:maintenance-mode-activate', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} var-set maintenance_mode 1 --sites-subdir={{site}}');
});

desc('Drush deactivate maintenance mode');
task('drupal7:drush:maintenance-mode-deactivate', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} var-set maintenance_mode 0 --sites-subdir={{site}}');
});

desc('Drush cache clear');
task('drupal7:drush:cache-clear', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} cache-clear all --sites-subdir={{site}}');
});

// Import all the config
desc('Drush feature revert all');
task('drupal7:drush:feature-revert-all', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} drush feature-revert-all -y --sites-subdir={{site}}');
});

// Import update all the entities
desc('Drush database updates');
task('drupal7:drush:updatedb', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} updatedb -y --sites-subdir={{site}}');
});

// Run cron
desc('Drush cron');
task('drupal7:drush:cron', function () {
  cd('{{release_path}}/{{web_dir_relative}}/sites/{{site}}');
  run('{{release_path}}/{{bin_drush}} cron -y --sites-subdir={{site}}');
});