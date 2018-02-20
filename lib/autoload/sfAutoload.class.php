<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfAutoload class.
 *
 * This class is a singleton as PHP seems to be unable to register 2 autoloaders that are instances
 * of the same class (why?).
 *
 * @package    symfony
 * @subpackage autoload
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfAutoload
{
  static protected
    $freshCache = false,
    $instance   = null;

  protected
    $overriden = array(),
    $classes   = array();

  protected function __construct()
  {
  }

  /**
   * Retrieves the singleton instance of this class.
   *
   * @return sfAutoload A sfAutoload implementation instance.
   */
  static public function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new sfAutoload();
    }

    return self::$instance;
  }

  /**
   * Register sfAutoload in spl autoloader.
   *
   * @return void
   *
   * @throws sfException
   */
  static public function register()
  {
    ini_set('unserialize_callback_func', 'spl_autoload_call');

    if (false === spl_autoload_register(array(self::getInstance(), 'autoload')))
    {
      throw new sfException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
    }
  }

  /**
   * Unregister sfAutoload from spl autoloader.
   *
   * @return void
   */
  static public function unregister()
  {
    spl_autoload_unregister(array(self::getInstance(), 'autoload'));
  }

  /**
   * Sets the path for a particular class.
   *
   * @param string $class A PHP class name
   * @param string $path  An absolute path
   */
  public function setClassPath($class, $path)
  {
    $class = strtolower($class);

    $this->overriden[$class] = $path;

    $this->classes[$class] = $path;
  }

  /**
   * Returns the path where a particular class can be found.
   *
   * @param string $class A PHP class name
   *
   * @return string|null An absolute path
   */
  public function getClassPath($class)
  {
    $class = strtolower($class);

    return isset($this->classes[$class]) ? $this->classes[$class] : null;
  }

  /**
   * Reloads the autoloader.
   *
   * @param  boolean $force Whether to force a reload
   *
   * @return boolean True if the reload was successful, otherwise false
   */
  public function reloadClasses($force = false)
  {
    // only (re)load the autoloading cache once per request
    if (self::$freshCache && !$force)
    {
      return false;
    }

    $configuration = sfProjectConfiguration::getActive();
    if (!$configuration || !$configuration instanceof sfApplicationConfiguration)
    {
      return false;
    }

    self::$freshCache = true;
    if (is_file($configuration->getConfigCache()->getCacheName('config/autoload.yml')))
    {
      self::$freshCache = false;
      if ($force)
      {
        if (file_exists($configuration->getConfigCache()->getCacheName('config/autoload.yml')))
        {
          unlink($configuration->getConfigCache()->getCacheName('config/autoload.yml'));
        }
      }
    }

    $file = $configuration->getConfigCache()->checkConfig('config/autoload.yml');

    if ($force && defined('HHVM_VERSION'))
    {
      // workaround for https://github.com/facebook/hhvm/issues/1447
      $this->classes = eval(str_replace('<?php', '', file_get_contents($file)));
    }
    else
    {
      $this->classes = include $file;
    }

    foreach ($this->overriden as $class => $path)
    {
      $this->classes[$class] = $path;
    }

    return true;
  }

  /**
   * Handles autoloading of classes that have been specified in autoload.yml.
   *
   * @param  string  $class  A class name.
   *
   * @return boolean Returns true if the class has been loaded
   */
  public function autoload($class)
  {
    // load the list of autoload classes
    if (!$this->classes)
    {
      self::reloadClasses();
    }

    return self::loadClass($class);
  }

  /**
   * Tries to load a class that has been specified in autoload.yml.
   *
   * @param  string  $class  A class name.
   *
   * @return boolean Returns true if the class has been loaded
   */
  public function loadClass($class)
  {
    $class = strtolower($class);

    // class already exists
    if (class_exists($class, false) || interface_exists($class, false) || (function_exists('trait_exists') && trait_exists($class, false)))
    {
      return true;
    }

    // we have a class path, let's include it
    if (isset($this->classes[$class]))
    {
      try
      {
        require $this->classes[$class];
      }
      catch (sfException $e)
      {
        $e->printStackTrace();
      }
      catch (Exception $e)
      {
        sfException::createFromException($e)->printStackTrace();
      }

      return true;
    }

    // see if the file exists in the current module lib directory
    if (
      sfContext::hasInstance()
      &&
      ($module = sfContext::getInstance()->getModuleName())
      &&
      isset($this->classes[$module.'/'.$class])
    )
    {
      try
      {
        require $this->classes[$module.'/'.$class];
      }
      catch (sfException $e)
      {
        $e->printStackTrace();
      }
      catch (Exception $e)
      {
        sfException::createFromException($e)->printStackTrace();
      }

      return true;
    }

    return false;
  }
}
