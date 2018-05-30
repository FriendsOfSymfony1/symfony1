<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all symfony tasks.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
abstract class sfBaseTask extends sfCommandApplicationTask
{
  protected
    $configuration   = null,
    $pluginManager   = null,
    $statusStartTime = null;

  /**
   * @see sfTask
   * @inheritdoc
   */
  protected function doRun(sfCommandManager $commandManager, $options)
  {
    $event = $this->dispatcher->filter(new sfEvent($this, 'command.filter_options', array('command_manager' => $commandManager)), $options);
    $options = $event->getReturnValue();

    $this->process($commandManager, $options);

    $event = new sfEvent($this, 'command.pre_command', array('arguments' => $commandManager->getArgumentValues(), 'options' => $commandManager->getOptionValues()));
    $this->dispatcher->notifyUntil($event);
    if ($event->isProcessed())
    {
      return $event->getReturnValue();
    }

    $this->checkProjectExists();

    $requiresApplication = $commandManager->getArgumentSet()->hasArgument('application') || $commandManager->getOptionSet()->hasOption('application');
    if (null === $this->configuration || ($requiresApplication && !$this->configuration instanceof sfApplicationConfiguration))
    {
      $application = $commandManager->getArgumentSet()->hasArgument('application') ? $commandManager->getArgumentValue('application') : ($commandManager->getOptionSet()->hasOption('application') ? $commandManager->getOptionValue('application') : null);
      $env = $commandManager->getOptionSet()->hasOption('env') ? $commandManager->getOptionValue('env') : 'test';

      if (true === $application)
      {
        $application = $this->getFirstApplication();

        if ($commandManager->getOptionSet()->hasOption('application'))
        {
          $commandManager->setOption($commandManager->getOptionSet()->getOption('application'), $application);
        }
      }

      $this->configuration = $this->createConfiguration($application, $env);
    }

    if (!$this->withTrace())
    {
      sfConfig::set('sf_logging_enabled', false);
    }

    $ret = $this->execute($commandManager->getArgumentValues(), $commandManager->getOptionValues());

    $this->dispatcher->notify(new sfEvent($this, 'command.post_command'));

    return $ret;
  }

  /**
   * Sets the current task's configuration.
   *
   * @param sfProjectConfiguration $configuration
   */
  public function setConfiguration(sfProjectConfiguration $configuration = null)
  {
    $this->configuration = $configuration;
  }

  /**
   * Returns the filesystem instance.
   *
   * @return sfFilesystem A sfFilesystem instance
   */
  public function getFilesystem()
  {
    if (!isset($this->filesystem))
    {
      if ($this->isVerbose())
      {
        $this->filesystem = new sfFilesystem($this->dispatcher, $this->formatter);
      }
      else
      {
        $this->filesystem = new sfFilesystem();
      }
    }

    return $this->filesystem;
  }

  /**
   * Checks if the current directory is a symfony project directory.
   *
   * @return true if the current directory is a symfony project directory, false otherwise
   *
   * @throws sfException
   */
  public function checkProjectExists()
  {
    if (!file_exists('symfony'))
    {
      throw new sfException('You must be in a symfony project directory.');
    }
  }

  /**
   * Checks if an application exists.
   *
   * @param  string $app The application name
   *
   * @return bool true if the application exists, false otherwise
   *
   * @throws sfException
   */
  public function checkAppExists($app)
  {
    if (!is_dir(sfConfig::get('sf_apps_dir').'/'.$app))
    {
      throw new sfException(sprintf('Application "%s" does not exist', $app));
    }
  }

  /**
   * Checks if a module exists.
   *
   * @param  string $app    The application name
   * @param  string $module The module name
   *
   * @return bool true if the module exists, false otherwise
   *
   * @throws sfException
   */
  public function checkModuleExists($app, $module)
  {
    if (!is_dir(sfConfig::get('sf_apps_dir').'/'.$app.'/modules/'.$module))
    {
      throw new sfException(sprintf('Module "%s/%s" does not exist.', $app, $module));
    }
  }

  /**
   * Checks if trace mode is enabled
   *
   * @return boolean
   */
  protected function withTrace()
  {
    if (null !== $this->commandApplication && !$this->commandApplication->withTrace())
    {
      return false;
    }

    return true;
  }

  /**
   * Checks if verbose mode is enabled
   *
   * @return boolean
   */
  protected function isVerbose()
  {
    if (null !== $this->commandApplication && !$this->commandApplication->isVerbose())
    {
      return false;
    }

    return true;
  }

  /**
   * Checks if debug mode is enabled
   *
   * @return boolean
   */
  protected function isDebug()
  {
    if (null !== $this->commandApplication && !$this->commandApplication->isDebug())
    {
      return false;
    }

    return true;
  }

  /**
   * Creates a configuration object.
   *
   * @param string  $application The application name
   * @param string  $env         The environment name
   *
   * @return sfProjectConfiguration A sfProjectConfiguration instance
   */
  protected function createConfiguration($application, $env)
  {
    if (null !== $application)
    {
      $this->checkAppExists($application);

      require_once sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';

      $configuration = ProjectConfiguration::getApplicationConfiguration($application, $env, $this->isDebug(), null, $this->dispatcher);
    }
    else
    {
      if (file_exists(sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php'))
      {
        require_once sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';
        $configuration = new ProjectConfiguration(null, $this->dispatcher);
      }
      else
      {
        $configuration = new sfProjectConfiguration(getcwd(), $this->dispatcher);
      }

      if (null !== $env)
      {
        sfConfig::set('sf_environment', $env);
      }

      $this->initializeAutoload($configuration);
    }

    return $configuration;
  }

  /**
   * Returns the first application in apps.
   *
   * @return string The Application name
   */
  protected function getFirstApplication()
  {
    if (count($dirs = sfFinder::type('dir')->maxdepth(0)->follow_link()->relative()->in(sfConfig::get('sf_apps_dir'))))
    {
      return $dirs[0];
    }

    return null;
  }

  /**
   * Reloads all autoloaders.
   *
   * This method should be called whenever a task generates new classes that
   * are to be loaded by the symfony autoloader. It clears the autoloader
   * cache for all applications and environments and the current execution.
   *
   * @see initializeAutoload()
   */
  protected function reloadAutoload()
  {
    $this->initializeAutoload($this->configuration, true);
  }

  /**
   * Initializes autoloaders.
   *
   * @param sfProjectConfiguration $configuration The current project or application configuration
   * @param boolean                $reload        If true, all autoloaders will be reloaded
   */
  protected function initializeAutoload(sfProjectConfiguration $configuration, $reload = false)
  {
    // sfAutoload
    if ($reload)
    {
      $this->logSection('autoload', 'Resetting application autoloaders');

      $finder = sfFinder::type('file')->name('*autoload.yml.php');
      $this->getFilesystem()->remove($finder->in(sfConfig::get('sf_cache_dir')));
      sfAutoload::getInstance()->reloadClasses(true);
    }

    // sfSimpleAutoload
    if (!$configuration instanceof sfApplicationConfiguration)
    {
      // plugins
      if ($reload)
      {
        foreach ($configuration->getPlugins() as $name)
        {
          $configuration->getPluginConfiguration($name)->initializeAutoload();
        }
      }

      // project
      $autoload = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
      $autoload->loadConfiguration(sfFinder::type('file')->name('autoload.yml')->in(array(
        sfConfig::get('sf_symfony_lib_dir').'/config/config',
        sfConfig::get('sf_config_dir'),
      )));
      $autoload->register();

      if ($reload)
      {
        $this->logSection('autoload', 'Resetting CLI autoloader');
        $autoload->reload();
      }
    }
  }

  /**
   * Mirrors a directory structure inside the created project.
   *
   * @param string   $dir    The directory to mirror
   * @param sfFinder $finder A sfFinder instance to use for the mirroring
   */
  protected function installDir($dir, $finder = null)
  {
    if (null === $finder)
    {
      $finder = sfFinder::type('any')->discard('.sf');
    }

    $this->getFilesystem()->mirror($dir, sfConfig::get('sf_root_dir'), $finder);
  }

  /**
   * Replaces tokens in files contained in a given directory.
   *
   * If you don't pass a directory, it will replace in the config/ and lib/ directory.
   *
   * You can define global tokens by defining the $this->tokens property.
   *
   * @param array $dirs   An array of directory where to do the replacement
   * @param array $tokens An array of tokens to use
   */
  protected function replaceTokens($dirs = array(), $tokens = array())
  {
    if (!$dirs)
    {
      $dirs = array(sfConfig::get('sf_config_dir'), sfConfig::get('sf_lib_dir'));
    }

    $tokens = array_merge(isset($this->tokens) ? $this->tokens : array(), $tokens);

    $this->getFilesystem()->replaceTokens(sfFinder::type('file')->prune('vendor')->in($dirs), '##', '##', $tokens);
  }

  /**
   * Reloads tasks.
   *
   * Useful when you install plugins with tasks and if you want to use them with the runTask() method.
   */
  protected function reloadTasks()
  {
    if (null === $this->commandApplication)
    {
      return;
    }

    $this->configuration = $this->createConfiguration(null, null);

    $this->commandApplication->clearTasks();
    $this->commandApplication->loadTasks($this->configuration);

    $disabledPluginsRegex = sprintf('#^(%s)#', implode('|', array_diff($this->configuration->getAllPluginPaths(), $this->configuration->getPluginPaths())));
    $tasks = array();
    foreach (get_declared_classes() as $class)
    {
      $r = new Reflectionclass($class);
      if ($r->isSubclassOf('sfTask') && !$r->isAbstract() && !preg_match($disabledPluginsRegex, $r->getFileName()))
      {
        $tasks[] = new $class($this->dispatcher, $this->formatter);
      }
    }

    $this->commandApplication->registerTasks($tasks);
  }

  /**
   * Enables a plugin in the ProjectConfiguration class.
   *
   * @param string $plugin The name of the plugin
   */
  protected function enablePlugin($plugin)
  {
    sfSymfonyPluginManager::enablePlugin($plugin, sfConfig::get('sf_config_dir'));
  }

  /**
   * Disables a plugin in the ProjectConfiguration class.
   *
   * @param string $plugin The name of the plugin
   */
  protected function disablePlugin($plugin)
  {
    sfSymfonyPluginManager::disablePlugin($plugin, sfConfig::get('sf_config_dir'));
  }

  /**
   * Returns a plugin manager instance.
   *
   * @return sfSymfonyPluginManager A sfSymfonyPluginManager instance
   */
  protected function getPluginManager()
  {
    if (null === $this->pluginManager)
    {
      $environment = new sfPearEnvironment($this->dispatcher, array(
        'plugin_dir' => sfConfig::get('sf_plugins_dir'),
        'cache_dir'  => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir'    => sfConfig::get('sf_web_dir'),
        'config_dir' => sfConfig::get('sf_config_dir'),
      ));

      $this->pluginManager = new sfSymfonyPluginManager($this->dispatcher, $environment);
    }

    return $this->pluginManager;
  }

  /**
   * @see sfCommandApplicationTask
   */
  protected function createTask($name)
  {
    $task = parent::createTask($name);

    if ($task instanceof sfBaseTask)
    {
      $task->setConfiguration($this->configuration);
    }

    return $task;
  }

  /**
   * Show status of task
   *
   * @param integer $done
   * @param integer $total
   * @param integer $size
   * @return void
   */
  protected function showStatus($done, $total, $size = 30)
  {
    // if we go over our bound, just ignore it
    if ($done > $total)
    {
      $this->statusStartTime = null;
      return;
    }

    if (null === $this->statusStartTime)
    {
      $this->statusStartTime = time();
    }

    $now = time();
    $perc = (double)($done / $total);
    $bar = floor($perc * $size);

    $statusBar = "\r[";
    $statusBar .= str_repeat('=', $bar);
    if ($bar < $size)
    {
      $statusBar .= '>';
      $statusBar .= str_repeat(' ', $size - $bar);
    }
    else
    {
      $statusBar .= "=";
    }

    $disp = number_format($perc * 100, 0);

    $statusBar .= "] $disp% ($done/$total)";

    $rate = $done ? ($now - $this->statusStartTime) / $done : 0;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $this->statusStartTime;

    $eta = $this->convertTime($eta);
    $elapsed = $this->convertTime($elapsed);

    $memory = memory_get_usage(true);
    if ($memory>1024*1024*1024*10)
    {
      $memory = sprintf('%.2fGB', $memory/1024/1024/1024);
    }
    elseif ($memory>1024*1024*10)
    {
      $memory = sprintf('%.2fMB', $memory/1024/1024);
    }
    elseif ($memory>1024*10)
    {
      $memory = sprintf('%.2fkB', $memory/1024);
    }
    else
    {
      $memory = sprintf('%.2fB', $memory);
    }

    $statusBar .= ' [ remaining: '.$eta.' | elapsed: '.$elapsed.' ] (memory: '.$memory.')     ';

    echo $statusBar;

    // when done, send a newline
    if ($done == $total)
    {
      $this->statusStartTime = null;
      echo "\n";
    }
  }

  /**
   * Convert time into humain format
   *
   * @param integer $time
   * @return string
   */
  private function convertTime($time)
  {
    $string = '';

    if ($time > 3600)
    {
      $h = (int) abs($time / 3600);
      $time -= ($h * 3600);
      $string .= $h. ' h ';
    }

    if ($time > 60)
    {
      $m = (int) abs($time / 60);
      $time -= ($m * 60);
      $string .= $m. ' min ';
    }

    $string .= (int) $time.' sec';

    return $string;
  }
}
