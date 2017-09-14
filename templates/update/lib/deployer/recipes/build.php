<?php
/**
 * @file
 * Contains all the different commands under the build step.
 */
namespace Deployer;

use Deployer\Task\Context; // This is loaded in by the deployer.phar and is accessible

/**
 * Build the project dependencies.
 */
desc('Run a full deploy from start to end. Building all the code on the build server and tarring it afterwards');
task('build:dependencies', function() {
  runLocally("{{local_workspace}}/{{bin_phing}} composer:build");
});

/**
 * Activate the environment we need.
 */
desc('Run a full deploy from start to end. Building all the code on the build server and tarring it afterwards');
task('build:activate-env', function() {
  runLocally("{{local_workspace}}/{{bin_phing}} project:activate-env -Denv.to.activate={{env}} -Dsite.to.activate={{site}}");
});

/**
 * Activate the stage we need.
 */
desc('Run a full deploy from start to end. Building all the code on the build server and tarring it afterwards');
task('build:activate-stage', function() {
  runLocally("{{local_workspace}}/{{bin_phing}} project:activate-site-stage -Dstage.to.activate={{stage}} -Dsite.to.activate={{site}}");
});

/**
 * Rebases the project phing data etc in such a way that it is compatible with
 * the final location of the project. This will skip out any hardcoded paths
 * set to the project buildspace and set them to match the project deploy space.
 */
desc('Rebase all the properties to the base dir for the actual deploy. This
prepares all the properties for their final location.');
task('build:rebase-project', function() {

  /*
   * We'll make a bit of an odd shift here.
   *
   * Either it's a "full" deploy. Which means the actual dir will be release/XX
   * which will be symlinked to current later (we can't rely on this since the
   * commands often run before the symlinking.
   *
   * Or it's not in which case it will release to the default platform root
   * location for the project.
   */
  if (get('env') == 'platform') {
    $finalReleaseLocation = get('root');
  }
  else {
    $releaseName = get('release_name');
    $finalReleaseLocation = get('root') . '/releases/' . $releaseName;
  }

  runLocally("{{local_workspace}}/{{bin_phing}} property:write-full -Dcurrent.basePath=" . $finalReleaseLocation . " -Dsite.to.activate={{site}}");
});