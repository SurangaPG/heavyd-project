<?php

namespace surangapg\Heavyd;

use surangapg\Heavyd\Command\Content\PrepareCommand as ContentPrepareCommand;
use surangapg\Heavyd\Command\Content\ExportCommand as ContentExportCommand;
use surangapg\Heavyd\Command\Content\ImportCommand as ContentImportCommand;

use surangapg\Heavyd\Command\DockerCompose\UpCommand as DockerComposeUpCommand;
use surangapg\Heavyd\Command\DockerCompose\StopCommand as DockerComposeStopCommand;
use surangapg\Heavyd\Command\DockerCompose\SetupCommand as DockerComposeSetupCommand;

use surangapg\Heavyd\Command\Env\SwitchCommand as EnvSwitchCommand;

use surangapg\Heavyd\Command\Haunt\RunCommand as HauntRunCommand;

use surangapg\Heavyd\Command\Misc\SetupCommand as MiscSetupCommand;
use surangapg\Heavyd\Command\Misc\InstallCommand as MiscInstallCommand;
use surangapg\Heavyd\Command\Misc\ResetCommand as MiscResetCommand;

use surangapg\Heavyd\Command\Docker\SeleniumCommand as DockerSeleniumCommand;

use surangapg\Heavyd\Command\Property\InfoCommand as PropertyInfoCommand;
use surangapg\Heavyd\Command\Property\RewriteCommand as PropertyRewriteCommand;
use surangapg\Heavyd\Command\Stage\SwitchCommand as StageSwitchCommand;
use surangapg\Heavyd\Components\Properties\Properties;
use surangapg\Heavyd\Components\Properties\PropertiesInterface;
use surangapg\Heavyd\Engine\DockerPhingEngine;
use surangapg\Heavyd\Engine\EngineInterface;
use surangapg\Heavyd\Engine\PhingEngine;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use surangapg\Heavyd\Command\Credential\CreateDefaultFileCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

class HeavydApplication extends Application {

  /**
   * The current version of the application. This will be used to validate
   * or the correct version has been instantiated. Making it easier to keep
   * the workflow application up to date and possibly add an upgrade option
   * later.
   * @const VERSION
   */
  const VERSION = '0.0.1';

  /**
   * The engine that does all the heavy lifting.
   *
   * Implemented like this to make it easier to swap to robo or something
   * similar later.
   *
   * @var EngineInterface
   */
  protected $engine;

  /**
   * Basepath for the project.
   *
   * @var string
   */
  protected $basePath;

  /**
   * All the properties for the project.
   *
   * @var \surangapg\Heavyd\Components\Properties\PropertiesInterface
   */
  protected $properties;

  /**
   * Creates and returns a fully functional workflow object based on the current
   * data in a selected .workflow.yml file (this is auto detected).
   *
   * @param string $basePath|null The base directory to start searching for
   *    the .workflow.yml file. Defaults to the current directory. if not set.
   *    This mainly allows for the testing of the application from alternate
   *    base locations.
   *
   * @return HeavydApplication
   *   Fully build application.
   * @throws \Exception
   */
  public static function create($basePath = null) {

    // Use the working directory if none was specified.
    if (!isset($basePath)) {
      $basePath = getcwd();
    }

    // Find the .workflow.yml file and use that as source of the settings
    $projectPath = self::defineBasePath($basePath);

    $properties = Properties::create($basePath);

    $dockerProperties = $properties->get('docker');
    if (isset($dockerProperties['containerActive']) && $dockerProperties['containerActive']) {
      $engine = new DockerPhingEngine($projectPath);
    }
    else {
      $engine = new PhingEngine($projectPath);
    }

    // Fully initialize the application with any extra data from the data file.
    $application = new self($engine, $properties);

    return $application;
  }

  /**
   * HeavydApplication constructor.
   *
   * @inheritdoc
   */
  public function __construct(EngineInterface $engine, PropertiesInterface $properties) {
    parent::__construct('HeavyD', static::VERSION);

    $this->engine = $engine;
    $this->properties = $properties;

    // Misc commands.
    $this->add(new MiscSetupCommand());
    $this->add(new MiscInstallCommand());
    $this->add(new MiscResetCommand());

    // Property commands.
    $this->add(new PropertyInfoCommand());
    $this->add(new PropertyRewriteCommand());

    // Content commands.
    $this->add(new ContentPrepareCommand());
    $this->add(new ContentExportCommand());
    $this->add(new ContentImportCommand());

    // Docker commands.
    $this->add(new DockerSeleniumCommand());

    // Docker Compose commands.
    $this->add(new DockerComposeSetupCommand());
    $this->add(new DockerComposeStopCommand());
    $this->add(new DockerComposeUpCommand());

    // Haunt commands.
    $this->add(new HauntRunCommand());

    $this->add(new EnvSwitchCommand());

    $this->add(new StageSwitchCommand());
    $this->add(new CreateDefaultFileCommand());
  }

  /**
   * @inheritdoc
   */
  public function add(Command $command)
  {
    $command = parent::add($command);

    if (isset($command)) {
      $command->addOption('silent-engine', 'S', InputOption::VALUE_NONE, 'Suppress all the output from the engine.');
    }
  }


  /**
   * Handle a run of a given command.
   *
   * @param InputInterface $input
   *   Input for the command.
   * @param OutputInterface $output
   *   Output generated by the command.
   *
   * @return int
   *   Exit code for the command being run.
   */
  public function doRun(InputInterface $input, OutputInterface $output) {

    /*
     * Display some information about the properties as needed.
     */
    $isInfo = (true === $input->hasParameterOption(array('--info', '-I'), true));
    if ($isInfo || $output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {

      $this->outputCurrentState(new SymfonyStyle($input, $output));

      if ($isInfo) {
        return 0;
      }
    }

    $isSilentEngine = (true === $input->hasParameterOption(array('--silent-engine', '-S'), true));
    $this->getEngine()->setSilent($isSilentEngine);
    $this->getEngine()->setOutput($output);

    parent::doRun($input, $output);
  }

  /**
   * Define the project path for this command.
   * @param string $basePath Basepath to start searching from
   * @return string|null
   * @throws \Exception
   */
  public static function defineBasePath($basePath = null) {

    $basePath = isset($basePath) ? $basePath : getcwd();

    $maxDepth = 100;
    $curDepth = 0;

    while (!file_exists($basePath . '/.heavyd.yml')) {
      $basePath = realpath($basePath . '/..');
      $curDepth++;
      if($curDepth > $maxDepth) {
        throw new \Exception("Couldn't locate .heavyd marker in any of the directories. Are you sure heavyd has been properly initialized?");
      }
    }

    return $basePath;
  }

  /**
   * Rebuild the current properties.
   *
   * Flushes and rebuilds the properties. This will acount for any changes
   * made to the property set during the course of a single command run.
   * For example when a command resets an environment and then needs access to
   * the properties of the new environment.
   */
  function rebuildProperties() {
    $this->setProperties(Properties::create($this->getProperties()->getBasePath()));
  }

  /**
   * Outputs the current state to the cli.
   *
   * @param \Symfony\Component\Console\Style\SymfonyStyle $io
   *   Symfony style to use.
   */
  function outputCurrentState(SymfonyStyle $io) {
    $projectProperties = $this->getProperties()->get('project');

    $io->writeln('<fg=yellow>Current state</>');
    $io->writeln(sprintf(' env: <fg=white>%s</>', $projectProperties['active']['env'] ? $projectProperties['active']['env'] : 'none'));
    $io->writeln(sprintf(' stage: <fg=white>%s</>', $projectProperties['active']['stage'] ? $projectProperties['active']['stage'] : 'none'));
    $io->writeln(sprintf(' site: <fg=white>%s</>', $projectProperties['active']['site'] ? $projectProperties['active']['site'] : 'none'));
    $io->newLine();

    $io->writeln('<fg=yellow>Current Engine</>');
    $io->writeln(sprintf(' engine: <fg=white>%s</>', get_class($this->engine)));
    $io->newLine();
  }

  /**
   * @return \surangapg\Heavyd\Engine\EngineInterface
   */
  public function getEngine() {
    return $this->engine;
  }

  /**
   * @param \surangapg\Heavyd\Engine\EngineInterface $engine
   */
  public function setEngine(EngineInterface $engine) {
    $this->engine = $engine;
  }

  /**
   * @return string
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * @param $basePath
   */
  public function setBasePath(string $basePath) {
    $this->basePath = $basePath;
  }

  /**
   * @return \surangapg\Heavyd\Components\Properties\PropertiesInterface
   */
  public function getProperties() {
    return $this->properties;
  }

  /**
   * @param \surangapg\Heavyd\Components\Properties\PropertiesInterface $properties
   */
  public function setProperties(PropertiesInterface $properties) {
    $this->properties = $properties;
  }
}
