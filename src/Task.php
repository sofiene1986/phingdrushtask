<?php

namespace Phing\Drush;

use PhingFile;

/**
 * Phing task to execute a Drush command.
 */
class Task extends \ExecTask {

  /**
   * The alias of the Drupal site to use.
   *
   * @var string
   */
  protected $alias = NULL;

  /**
   * Specifies the list of paths where drush will search for alias files.
   *
   * @var string
   */
  protected $aliasPath = NULL;

  /**
   * If set, assume 'yes' or 'no' as answer to all prompts.
   *
   * @var bool
   */
  protected $assume = FALSE;

  /**
   * Path the the Drush binary.
   *
   * @var PhingFile
   */
  protected $bin = 'drush';

  /**
   * Whether to check the return code.
   *
   * @var bool
   */
  protected $checkreturn = TRUE;

  /**
   * Whether or not to use colored output.
   *
   * @var bool
   */
  protected $color = FALSE;

  /**
   * Path to an additional config file to load.
   *
   * @var string
   */
  protected $config = NULL;

  /**
   * Working directory.
   *
   * @var PhingFile
   */
  protected $dir;

  /**
   * Whether to use PHP's passthru() function instead of exec().
   *
   * @var bool
   */
  protected $passthru = TRUE;

  /**
   * Use the pipe option.
   *
   * @var bool
   */
  protected $pipe = FALSE;

  /**
   * Drupal root directory to use.
   *
   * @var PhingFile
   */
  protected $root = NULL;

  /**
   * If true, simulate all relevant actions.
   *
   * @var bool
   */
  protected $simulate = FALSE;

  /**
   * URI of the Drupal site to use.
   *
   * @var PhingFile
   */
  protected $uri = NULL;

  /**
   * Display extra information about the command.
   *
   * @var bool
   */
  protected $verbose = FALSE;

  /**
   * An array of Option.
   *
   * @var Option[]
   */
  protected $options = array();

  /**
   * An array of Param.
   *
   * @var Param[]
   */
  protected $params = array();

  /**
   * Task constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->setExecutable($this->bin);
  }

  /**
   * Set the site alias.
   *
   * @param string $alias
   *   The site alias.
   */
  public function setAlias($alias) {
    $this->alias = $alias;
  }

  /**
   * Set the list of paths where drush will search for alias files.
   *
   * @param string $aliasPath
   *   The list of paths.
   */
  public function setAliasPath($aliasPath) {
    $this->createOption()
      ->setName('aliaspath')
      ->addText($aliasPath);
  }

  /**
   * Set the assume option. 'yes' or 'no' to all prompts.
   *
   * @param bool $yesNo
   *   The assume option.
   */
  public function setAssume($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('yes');
    }
    else {
      $this->createOption()->setName('no');
    }
  }

  /**
   * Hide all output and return structured data.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setBackend($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('backend');
    }
  }

  /**
   * Specifies the directory where drush will store backups.
   *
   * @param \PhingFile $directory
   *   The directory.
   */
  public function setBackupLocation(PhingFile $directory) {
    $this->createOption()
      ->setName('backup-location')
      ->addText($directory->getAbsolutePath());
  }

  /**
   * Path the Drush executable.
   *
   * @param string $bin
   *   The path to the Drush executable.
   */
  public function setBin($bin) {
    $this->bin = new PhingFile($bin);
    $this->setExecutable($this->bin);
  }

  /**
   * Whether or not to use color output.
   *
   * @param bool $yesNo
   *   The color option.
   */
  public function setColor($yesNo) {
    if (!$yesNo) {
      $this->createOption()
        ->setName('nocolor');
    }
  }

  /**
   * Set the path to an additional config file to load.
   *
   * @param PhingFile $config
   *   The path to the additional config file to load.
   */
  public function setConfig(PhingFile $config) {
    $this->createOption()
      ->setName('config')
      ->addText($config->getAbsolutePath());
  }

  /**
   * The Drush command to run.
   *
   * @param string $command
   *   The Drush command's name.
   */
  public function setCommand($command) {
    $this->createParam()->addText($command);
  }

  /**
   * Display even more information, including internal messages.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setDebug($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('debug');
    }
  }

  /**
   * Shows the Druplicon as glorious ASCII art.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setDruplicon($yesNo) {
    if ($yesNo) {
      $this->createOption()
        ->setName('druplicon');
    }
  }

  /**
   * Controls error handling of recoverable errors.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setHaltOnError($yesNo) {
    if ($yesNo) {
      $this->createOption()
        ->setName('halt-on-error')
        ->addText(\StringHelper::booleanValue($yesNo));
    }
  }

  /**
   * Suppress non-error messages.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setQuiet($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('quiet');
    }
  }

  /**
   * Drupal root directory to use.
   *
   * @param string $root
   *   The Drupal's root directory to use.
   */
  public function setRoot($root) {
    $this->createOption()
      ->setName('root')
      ->addText($root);
  }

  /**
   * Show all functions names called for the current command.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setShowInvoke($yesNo) {
    if ($yesNo) {
      $this->createOption()
        ->setName('show-invoke')
        ->addText(\StringHelper::booleanValue($yesNo));
    }
  }

  /**
   * Show database passwords in commands that display connection information.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setShowPasswords($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('show-passwords');
    }
  }

  /**
   * Set the the pipe option.
   *
   * @param bool $pipe
   *   The pipe option.
   */
  public function setPipe($pipe) {
    $this->createOption()
      ->setName('pipe')
      ->addText($pipe);
  }

  /**
   * This is not a real drush option. It's just used for tests.
   *
   * Display the command that would be runned only.
   *
   * @param bool $yesNo
   *   The pretend option.
   */
  public function setPretend($yesNo) {
    if ($yesNo) {
      $this->createOption()
        ->setName('pretend');
    }
  }

  /**
   * Set the simulate option.
   *
   * @param bool $yesNo
   *   The simulate option.
   */
  public function setSimulate($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('simulate');
    }
  }

  /**
   * URI of the Drupal to use.
   *
   * @param string $uri
   *   The URI of the Drupal site to use.
   */
  public function setUri($uri) {
    $this->createOption()
      ->setName('uri')
      ->addText($uri);
  }

  /**
   * Specify a user to login with. May be a name or a number.
   *
   * @param string $user
   *   The user name.
   */
  public function setUser($user) {
    $this->createOption()
      ->setName('user')
      ->addText($user);
  }

  /**
   * Return an error on unrecognized options.
   *
   * @param int $strict
   *   The strict level.
   */
  public function setStrict($strict) {
    $this->createOption()
      ->setName('strict')
      ->addText($strict);
  }

  /**
   * Display extra information about the command.
   *
   * @param bool $yesNo
   *   The verbose option.
   */
  public function setVerbose($yesNo) {
    if (\StringHelper::booleanValue($yesNo)) {
      $this->createOption()->setName('verbose');
    }
  }

  /**
   * Show drush version.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setVersion($yesNo) {
    if ($yesNo) {
      $this->createOption()->setName('version');
    }
  }

  /**
   * Enable profiling via XHProf.
   *
   * @param bool $yesNo
   *   The value.
   */
  public function setXdebug($yesNo) {
    if ($yesNo) {
      $this->createOption()
        ->setName('xh');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function init() {
    // Get default properties from project.
    $properties_mapping = array(
      'setAlias' => 'drush.alias',
      'setAliasPath' => 'drush.alias-path',
      'setAssume' => 'drush.assume',
      'setBin' => 'drush.bin',
      'setColor' => 'drush.color',
      'setConfig' => 'drush.config',
      'setPipe' => 'drush.pipe',
      'setRoot' => 'drush.root',
      'setSimulate' => 'drush.simulate',
      'setUri' => 'drush.uri',
      'setVerbose' => 'drush.verbose',
    );

    foreach ($properties_mapping as $class_method => $drush_property) {
      if ($property = $this->getProject()->getProperty($drush_property)) {
        call_user_func(array($this, $class_method), $property);
      }
    }
  }

  /**
   * Parameters of the Drush command.
   *
   * @return Param
   *   The created parameter.
   */
  public function createParam() {
    $num = array_push($this->params, new Param());
    return $this->params[$num - 1];
  }

  /**
   * Options of the Drush command.
   *
   * @return Option
   *   The created option.
   */
  public function createOption() {
    $num = array_push($this->options, new Option());
    return $this->options[$num - 1];
  }

  /**
   * The main entry point method.
   */
  public function main() {
    /*
     * The Drush binary command.
     */
    if ($this->bin instanceof PhingFile) {
      $this->setBin($this->bin);
    }
    $this->commandline->setExecutable($this->bin);

    /*
     * The site alias.
     */
    if ($this->alias) {
      $this->commandline->addArguments((array) $this->alias);
    }

    /*
     * The options.
     */
    $options = array();

    // This has been specifically made for tests.
    // If the pretend option has been found, just display the drush command
    // but never execute it.
    $pretend = NULL;
    if ($pretend = $this->optionExists('pretend')) {
      $this->setLogoutput(FALSE);
      $this->setPassthru(FALSE);
      $this->setCheckreturn(FALSE);
      $this->optionRemove('pretend');
    }
    $this->commandline->addArguments($this->options);

    /*
     * The parameters.
     */
    $params = array();
    foreach ($this->params as $param) {
      $params[] = $param->toString();
    }
    $this->commandline->addArguments($params);

    $this->buildCommand();
    $this->log('Executing command: ' . $this->realCommand);

    if (!$pretend) {
      parent::main();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function buildCommand() {
    $this->realCommand = implode(' ', $this->commandline->getCommandline());

    if ($this->error !== NULL) {
      $this->realCommand .= ' 2> ' . escapeshellarg($this->error->getPath());
      $this->log(
        "Writing error output to: " . $this->error->getPath(),
        $this->logLevel
      );
    }

    if ($this->output !== NULL) {
      $this->realCommand .= ' 1> ' . escapeshellarg($this->output->getPath());
      $this->log(
        "Writing standard output to: " . $this->output->getPath(),
        $this->logLevel
      );
    }
    elseif ($this->spawn) {
      $this->realCommand .= ' 1>/dev/null';
      $this->log("Sending output to /dev/null", $this->logLevel);
    }

    // If neither output nor error are being written to file
    // then we'll redirect error to stdout so that we can dump
    // it to screen below.
    if ($this->output === NULL && $this->error === NULL && $this->passthru === FALSE) {
      $this->realCommand .= ' 2>&1';
    }

    // We ignore the spawn boolean for windows.
    if ($this->spawn) {
      $this->realCommand .= ' &';
    }
  }

  /**
   * Check if an option exists.
   *
   * @param string $optionName
   *   The option name.
   *
   * @return array|\Phing\Drush\Option[]
   *   The option if exists, an empty array otherwise.
   */
  private function optionExists($optionName) {
    return array_filter($this->options, function ($option) use ($optionName) {
      return $option->getName() == $optionName;
    });
  }

  /**
   * Remove an option.
   *
   * @param string $optionName
   *   The option name.
   *
   * @return \Phing\Drush\Option[]
   *   The option array without the option to remove.
   */
  private function optionRemove($optionName) {
    $this->options = array_filter($this->options, function ($option) use ($optionName) {
      return $option->getName() != $optionName;
    });

    return $this->options;
  }

}
