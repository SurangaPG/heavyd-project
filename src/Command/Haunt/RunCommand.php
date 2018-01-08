<?php

namespace surangapg\Heavyd\Command\Haunt;

use surangapg\Heavyd\Command\AbstractHeavydCommandBase;
use surangapg\Heavyd\Command\Base\DockerCommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class RunCommand extends AbstractHeavydCommandBase {

  use DockerCommandTrait;

  /**
   * @var string
   */
  protected $browser = 'firefox';

  /**
   * @var string
   */
  protected $file = 'full.yml';

  /**
   * @var string
   */
  protected $domain;

  /**
   * @var string
   */
  protected $type = 'comparison';

  /**
   * @inheritdoc
   */
  protected function configure() {
    $this->setName('haunt:run')
      ->addOption('browser', NULL, InputOption::VALUE_REQUIRED, 'firefox')
      ->addOption('file', NULL, InputOption::VALUE_REQUIRED, 'full')
      ->addOption('domain', NULL, InputOption::VALUE_REQUIRED)
      ->setDescription('This will provide you with some prompts to run haunt from the command line.');
  }

  /**
   * @inheritdoc
   */
  public function initialize(InputInterface $input, OutputInterface $output) {
    parent::initialize($input, $output);

    if (!$this->checkContainer('selenium')) {
      $projectProperties = $this->getProperties()->get('project');
      $containerName = $projectProperties['group'] . '_' . $projectProperties['machineName'] . '_selenium';
      $this->getIo()->warning(sprintf('Docker container "%s" not found, you can use "heavyd docker:selenium" to start it.', $containerName));
      exit(1);
    }

    if (!empty($input->getOption('browser'))) {
      $this->browser = $input->getOption('browser');
    }

    if (!empty($input->getOption('file'))) {
      $this->browser = $input->getOption('file');
    }

    if (!empty($input->getOption('domain'))) {
      $this->domain = $input->getOption('domain');
    }
  }

  /**
   * @inheritdoc
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    parent::interact($input, $output);

    $this->type = $this->getIo()->choice('What do you want?', ['baseline' => 'New baseline reference', 'comparison' => 'New comparison'], 'comparison');
    $hauntProperties = $this->getProperties()->get('haunt');

    if ($this->type == 'baseline' && !isset($this->domain)) {
      $this->domain = $this->getIo()->ask('What domain should be used for the baseline?', $hauntProperties['domains']['baseline']);
    }
    elseif (!isset($this->domain)) {
      $hostProperties = $this->getProperties()->get('host');
      $hostDomain = isset($hostProperties['default']['domain']) ? $hostProperties['default']['domain'] : '';
      $this->domain = $this->getIo()->ask('What domain should be used for the comparison?', $hostDomain);
    }

    $dirProperties = $this->getProperties()->get('dir');
    $configFile = $dirProperties['tests']['haunt'] . '/' . $this->file;
    if (!file_exists($configFile)) {
      $this->getIo()->warning(sprintf('File %s does not exist.', $configFile));
      $this->file = NULL;
    }

    if (!isset($this->file)) {
      $files = glob($dirProperties['tests']['haunt'] . '/*.yml');
      $options = [];
      foreach ($files as $file) {
        $options[basename($file)] = basename($file);
      }

      $this->file = $this->getIo()->choice('Which file do you want to use', $options);
    }
  }

  /**
   * @inheritdoc
   */
  public function execute(InputInterface $input, OutputInterface $output) {

    $hauntProperties = $this->getProperties()->get('haunt');

    if ($this->type == 'baseline') {
      $this->getIo()->writeln('<fg=white>Generating baseline images:</>');
      $this->getEngine()->taskProjectGenerateVisualBaseline($this->file, $this->domain, $this->browser);
    }
    elseif ($this->type == 'comparison') {
      $this->getIo()->writeln('<fg=white>Comparing to baseline images:</>');
      $this->getEngine()->taskProjectRunVisualComparison($this->file, $this->domain, $this->browser);

      // Print out the report to the CLI in a cleaner (colored) fashion.
      $this->getIo()->newLine();
      $this->getIo()->title('Report Summary');
      $this->getIo()->newLine();

      $reportFile = $hauntProperties['output'] . '/report.json';
      $report = json_decode(file_get_contents($reportFile), TRUE);

      foreach($report['records'] as $set) {
        $this->getIo()->writeln('<fg=yellow>Checking results for ' . $set['info']['id'] . '</>');

        foreach ($set['paths'] as $path) {
          if ($path['diff'] > 0) {
            $this->getIo()->writeln('Detected <fg=red>' . $path['diff'] . '%</> difference in ' . $path['folder']);
          }
          else {
            $this->getIo()->writeln('No difference <fg=green>' . $path['diff'] . '%</> difference ' . $path['folder']);
          }
        }

        $this->getIo()->newLine();
      }
    }
  }

}
