<?php

namespace Phing\Drush;

use PhingFile;

/**
 * Phing task to execute a Drush command.
 */
class Task extends \Task {

  /**
   * The command to execute.
   *
   * @var string
   */
  protected $command = NULL;

  /**
   * Path the the Drush binary.
   *
   * @var PhingFile
   */
  protected $bin = 'drush';

  /**
   * URI of the Drupal site to use.
   *
   * @var PhingFile
   */
  protected $uri = NULL;

  /**
   * Drupal root directory to use.
   *
   * @var PhingFile
   */
  protected $root = NULL;

  /**
   * If set, assume 'yes' or 'no' as answer to all prompts.
   *
   * @var bool
   */
  protected $assume = FALSE;

  /**
   * If true, simulate all relevant actions.
   *
   * @var bool
   */
  protected $simulate = FALSE;

  /**
   * Use the pipe option.
   *
   * @var bool
   */
  protected $pipe = FALSE;

  /**
   * The 'glue' characters used between each line of the returned output.
   *
   * @var string
   */
  protected $returnGlue = "\n";

  /**
   * The name of a Phing property to assign the Drush command's output to.
   *
   * @var string
   */
  protected $returnProperty = NULL;

  /**
   * Display extra information about the command.
   *
   * @var bool
   */
  protected $verbose = FALSE;

  /**
   * Should the build fail on Drush errors.
   *
   * @var bool
   */
  protected $haltOnError = TRUE;

  /**
   * The alias of the Drupal site to use.
   *
   * @var string
   */
  protected $alias = NULL;

  /**
   * Path to an additional config file to load.
   *
   * @var string
   */
  protected $config = NULL;

  /**
   * Specifies the list of paths where drush will search for alias files.
   *
   * @var string
   */
  protected $aliasPath = NULL;

  /**
   * Whether or not to use colored output.
   *
   * @var bool
   */
  protected $color = FALSE;

  /**
   * Working directory.
   *
   * @var PhingFile
   */
  protected $dir;

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
   * If spawn is set then [unix] programs will redirect stdout and add '&'.
   * @var boolean
   */
  protected $spawn = false;

  /**
   * Whether to suppress all output and run in the background.
   *
   * @param boolean $spawn
   *   If the command is to be run in the background
   */
  public function setSpawn($spawn) {
    $this->spawn = (bool) $spawn;
  }

  /**
   * The Drush command to run.
   *
   * @param string $command
   *   The Drush command's name.
   */
  public function setCommand($command) {
    $this->command = $command;
  }

  /**
   * Path the Drush executable.
   *
   * @param PhingFile $bin
   *   The path to the Drush executable.
   */
  public function setBin(PhingFile $bin) {
    $this->bin = $bin;
  }

  /**
   * Drupal root directory to use.
   *
   * @param PhingFile $root
   *   The Drupal's root directory to use.
   */
  public function setRoot(PhingFile $root) {
    $this->root = $root;
  }

  /**
   * URI of the Drupal to use.
   *
   * @param string $uri
   *   The URI of the Drupal site to use.
   */
  public function setUri($uri) {
    $this->uri = $uri;
  }

  /**
   * Set the assume option. 'yes' or 'no' to all prompts.
   *
   * @param string $assume
   *   The assume option.
   */
  public function setAssume($assume) {
    $this->assume = $assume;
  }

  /**
   * Set the simulate option.
   *
   * @param string $simulate
   *   The simulate option.
   */
  public function setSimulate($simulate) {
    $this->simulate = $simulate;
  }

  /**
   * Set the the pipe option.
   *
   * @param bool $pipe
   *   The pipe option.
   */
  public function setPipe($pipe) {
    $this->pipe = $pipe;
  }

  /**
   * The 'glue' characters used between each line of the returned output.
   *
   * @param string $glue
   *   The glue character.
   */
  public function setReturnGlue($glue) {
    $this->returnGlue = (string) $glue;
  }

  /**
   * The name of a Phing property to assign the Drush command's output to.
   *
   * @param string $property
   *   The property's name.
   */
  public function setReturnProperty($property) {
    $this->returnProperty = $property;
  }

  /**
   * Should the task fail on Drush error (non zero exit code).
   *
   * @param string $haltOnError
   *   The value of the Halt On Error option.
   */
  public function setHaltOnError($haltOnError) {
    $this->haltOnError = $haltOnError;
  }
  /**
   * Display extra information about the command.
   *
   * @param bool $verbose
   *   The verbose option.
   */
  public function setVerbose($verbose) {
    $this->verbose = $verbose;
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
   * Set the path to an additional config file to load.
   *
   * @param PhingFile $config
   *   The path to the additional config file to load.
   */
  public function setConfig(PhingFile $config) {
    $this->config = $config;
  }

  /**
   * Set the list of paths where drush will search for alias files.
   *
   * @param string $aliasPath
   *   The list of paths.
   */
  public function setAliasPath($aliasPath) {
    $this->aliasPath = $aliasPath;
  }

  /**
   * Whether or not to use color output.
   *
   * @param bool $color
   *   The color option.
   */
  public function setColor($color) {
    $this->color = $color;
  }

  /**
   * Specify the working directory for executing this command.
   *
   * @param PhingFile $dir
   *   Working directory.
   */
  public function setDir(PhingFile $dir) {
    $this->dir = $dir;
  }

  /**
   * {@inheritdoc}
   */
  public function init() {
    // Get default properties from project.
    $properties_mapping = array(
      'alias' => 'drush.alias',
      'aliasPath' => 'drush.alias-path',
      'assume' => 'drush.assume',
      'bin' => 'drush.bin',
      'color' => 'drush.color',
      'config' => 'drush.config',
      'pipe' => 'drush.pipe',
      'root' => 'drush.root',
      'simulate' => 'drush.simulate',
      'uri' => 'drush.uri',
      'verbose' => 'drush.verbose',
    );

    foreach ($properties_mapping as $class_property => $drush_property) {
      if (!empty($this->getProject()->getProperty($drush_property))) {
        // TODO: We should use a setter here.
        $this->{$class_property} = $this->getProject()->getProperty($drush_property);
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
    $command = array();

    /**
     * The Drush binary command.
     */
    $command[] = $this->bin->getAbsolutePath();

    /**
     * The site alias.
     */
    if ($this->alias) {
      $command[] = $this->alias;
    }

    /**
     * The options
     */
    if (!$this->color) {
      $option = new Option();
      $option->setName('nocolor');
      $this->options[] = $option;
    }

    if ($this->root) {
      $option = new Option();
      $option->setName('root');
      $option->addText($this->root);
      $this->options[] = $option;
    }

    if ($this->uri) {
      $option = new Option();
      $option->setName('uri');
      $option->addText($this->uri->getAbsolutePath());
      $this->options[] = $option;
    }

    if ($this->config) {
      $option = new Option();
      $option->setName('config');
      $option->addText($this->config);
      $this->options[] = $option;
    }

    if ($this->aliasPath) {
      $option = new Option();
      $option->setName('alias-path');
      $option->addText($this->aliasPath);
      $this->options[] = $option;
    }

    if ($this->assume) {
      $option = new Option();
      $option->setName(($this->assume ? 'yes' : 'no'));
      $this->options[] = $option;
    }

    if ($this->simulate) {
      $option = new Option();
      $option->setName('simulate');
      $this->options[] = $option;
    }

    if ($this->pipe) {
      $option = new Option();
      $option->setName('pipe');
      $this->options[] = $option;
    }

    if ($this->verbose) {
      $option = new Option();
      $option->setName('verbose');
      $this->options[] = $option;
    }

    foreach ($this->options as $option) {
      $command[] = $option->toString();
    }

    /**
     * The Drush command to run.
     */
    $command[] = $this->command;

    /**
     * The parameters.
     */
    foreach ($this->params as $param) {
      $command[] = $param->toString();
    }

    // we ignore the spawn boolean for windows
    if ($this->spawn) {
      $command[] = '>/dev/null 2>&1';
      $command[] = '&';
    }

    $command = implode(' ', $command);

    if ($this->dir !== NULL) {
      $currdir = getcwd();
      @chdir($this->dir->getPath());
    }

    // Execute Drush.
    $this->log("Executing: " . $command);
    $output = array();
    exec($command, $output, $return);

    if (isset($currdir)) {
      @chdir($currdir);
    }

    // Collect Drush output for display through Phing's log.
    foreach ($output as $line) {
      $this->log($line);
    }

    // Set value of the 'pipe' property.
    if (!empty($this->returnProperty)) {
      $this->getProject()->setProperty($this->returnProperty, implode($this->returnGlue, $output));
    }

    // When build failed.
    if ($this->haltOnError && $return != 0) {
      throw new \BuildException("Drush exited with code: " . $return);
    }

    return $return != 0;
  }

}
