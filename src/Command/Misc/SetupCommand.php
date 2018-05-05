<?php

/**
 * @file Contains the command that makes all the actual comparisons.
 */

namespace surangapg\Heavyd\Command\Misc;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class SetupCommand extends AbstractHeavydCommandBase {

  /**
   * Stage to install.
   *
   * @var string
   */
  public $targetStage;

  /**
   * Env to install.
   *
   * @var string
   */
  public $targetEnv;

  /**
   * Site to install.
   *
   * @var string
   */
  public $targetSite;

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('setup')
      ->setDescription('Set up a local project.');
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    // @TODO add none interactive support
    if(!$input->isInteractive()) {
      $this->getIo()->error('Can\'t run this non interactively');
      exit;
    }

    $this->targetStage = $this->askStage();
    $this->targetEnv = $this->askEnv();
    $this->targetSite = $this->askSite();
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {

    $this->getIo()->title('Assembling codebase');
    // Start by preparing the project with the needed data from the etc folders.
    $this->getEngine()->taskProjectSwitchEnv($this->targetEnv);
    $this->getEngine()->taskProjectSwitchStage($this->targetStage);
    $this->getEngine()->taskProjectSwitchSite($this->targetSite);

    $this->getEngine()->taskProjectWriteProperties();
    $this->reloadProperties();
    $this->getIo()->newLine();

    // Check or any local properties are required.
    $projectProperties = $this->getProperties()->get('project');
    if (isset($projectProperties['requires_local_properties']) && $projectProperties['requires_local_properties']) {
      $this->runLocalHostPropertiesHandler();
      $this->runLocalDbPropertiesHandler();
    }

    $this->getIo()->title('Install site');
    $this->outputCurrentState();
    /*
     * If everything has been handled you should now be able to just install
     * the site without any problems.
     */
    $this->getIo()->writeln('');
    $install = $this->getIo()->confirm('Do you want to install the site now?');
    if ($install) {
      $this->getEngine()->taskProjectInstall($this->targetEnv, $this->targetStage, $this->targetSite);
    }

    $this->getIo()->success('Setup Completed');
  }

  /**
   * Adds the basic information for the local host uri.
   */
  public function runLocalHostPropertiesHandler() {
    // Select a uri.
    $this->getIo()->title('Setting up local host');

    $dirs = $this->getProperties()->get('dir');

    $fs = new Filesystem();

    $localHostFile = $dirs['etc']['env'] . '/local/properties/host.yml';

    if (file_exists($localHostFile)) {
      $localHostData = Yaml::parse(file_get_contents($localHostFile));
    }

    $localHostData = isset($localHostData) ? $localHostData : ['default' => ['domain' => NULL]];

    foreach ($localHostData as $siteName => &$domain) {
      $domain['domain'] = $this->getIo()->ask('What is the local domain (e.g http://mysite.local)', $domain['domain']);
    }

    // Save the data to the local env
    $fs->dumpFile($localHostFile, Yaml::dump($localHostData, 5, 2));
    $this->getIo()->newLine();
  }

  /**
   * Adds the basic information for the local server. Based on a few prompts.
   *
   * Ask the user to define his or her local db credentials. These will then
   * be written into the etc/env/local/properties.
   * Overwritten since it doesn't seem to use the correct keys.
   */
  public function runLocalDbPropertiesHandler() {

    $this->getIo()->title('Setting up local database information');

    $dirs = $this->getProperties()->get('dir');

    $fs = new Filesystem();

    $localDbData = [];

    $localDbDataFile = $dirs['etc']['env'] . '/' . $this->targetEnv . '/properties/db.yml';
    $defaultData = [];
    if (file_exists($localDbDataFile)) {
      $defaultData = Yaml::parse(file_get_contents($localDbDataFile));
    }

    /*
     * Run over the listed db services (as defined in the properties) and
     * provide the local settings.
     */
    $dbServices = $this->getProperties()->get('services');

    // If no specifics were added we'll just expect the standard settings.
    $dbServices = isset($dbServices['databases']) ?
      $dbServices['databases'] : ['default' => 'default--default'];

    foreach ($dbServices as $site => $dbSettingNames) {

      $this->getIo()->writeln('<fg=yellow>Handle DB settings for site "' . $site . '"</>');


      // Ask questions for every DB service.
      foreach ($dbSettingNames as $dbSettingName) {

        list($key1, $key2) = explode('--', $dbSettingName);

        $this->getIo()->writeln('<fg=yellow>Handle settings for "' . $key1 . ':' . $key2 . '"</>');

        $questions = [
          'username' => [
            'question' => 'Database username',
            'default' => isset($defaultData[$site][$key1][$key2]['username'])
              ? $defaultData[$site][$key1][$key2]['username'] : null,
          ],
          'password' => [
            'question' => 'Database password',
            'default' => isset($defaultData[$site][$key1][$key2]['password'])
              ? $defaultData[$site][$key1][$key2]['password'] : null,
          ],
          'database' => [
            'question' => 'Database name',
            'default' => isset($defaultData[$site][$key1][$key2]['database'])
              ? $defaultData[$site][$key1][$key2]['database'] : null,
          ],
          'host' => [
            'question' => 'Database host',
            'default' => isset($defaultData[$site][$key1][$key2]['host'])
              ? $defaultData[$site][$key1][$key2]['host'] : 'localhost',
          ],
        ];

        foreach ($questions as $property => $question) {
          $localDbData[$site][$key1][$key2][$property] =
            $this->getIo()->ask($question['question'], $question['default']);
        }
      }
    }

    // Save the data to the local env
    $fs->dumpFile($localDbDataFile, Yaml::dump($localDbData, 5, 2));
    $this->getIo()->newLine();
  }
}