<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Clears the symfony cache.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfCacheClearTask extends sfBaseTask
{
  protected
    $config = null;

  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('app', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', null),
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'The type', 'all'),
    ));

    $this->aliases = array('cc');
    $this->namespace = 'cache';
    $this->name = 'clear';
    $this->briefDescription = 'Clears the cache';

    $this->detailedDescription = <<<EOF
The [cache:clear|INFO] task clears the symfony cache.

By default, it removes the cache for all available types, all applications,
and all environments.

You can restrict by type, application, or environment:

For example, to clear the [frontend|COMMENT] application cache:

  [./symfony cache:clear --app=frontend|INFO]

To clear the cache for the [prod|COMMENT] environment for the [frontend|COMMENT] application:

  [./symfony cache:clear --app=frontend --env=prod|INFO]

To clear the cache for all [prod|COMMENT] environments:

  [./symfony cache:clear --env=prod|INFO]

To clear the [config|COMMENT] cache for all [prod|COMMENT] environments:

  [./symfony cache:clear --type=config --env=prod|INFO]

The built-in types are: [config|COMMENT], [i18n|COMMENT], [routing|COMMENT], [module|COMMENT]
and [template|COMMENT].

EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfConfig::get('sf_cache_dir') || !is_dir(sfConfig::get('sf_cache_dir')))
    {
      throw new sfException(sprintf('Cache directory "%s" does not exist.', sfConfig::get('sf_cache_dir')));
    }

    // finder to find directories (1 level) in a directory
    $dirFinder = sfFinder::type('dir')->discard('.*')->maxdepth(0)->relative();

    // iterate through applications
    $apps = null === $options['app'] ? $dirFinder->in(sfConfig::get('sf_apps_dir')) : array($options['app']);
    foreach ($apps as $app)
    {
      $this->checkAppExists($app);

      if (!is_dir(sfConfig::get('sf_cache_dir').'/'.$app))
      {
        continue;
      }

      // iterate through environments
      $envs = null === $options['env'] ? $dirFinder->in(sfConfig::get('sf_cache_dir').'/'.$app) : array($options['env']);
      foreach ($envs as $env)
      {
        if (!is_dir(sfConfig::get('sf_cache_dir').'/'.$app.'/'.$env))
        {
          continue;
        }

        $this->logSection('cache', sprintf('Clearing cache type "%s" for "%s" app and "%s" env', $options['type'], $app, $env));

        $appConfiguration = ProjectConfiguration::getApplicationConfiguration($app, $env, true);

        $this->lock($app, $env);

        $event = $appConfiguration->getEventDispatcher()->notifyUntil(new sfEvent($this, 'task.cache.clear', array('app' => $appConfiguration, 'env' => $env, 'type' => $options['type'])));
        if (!$event->isProcessed())
        {
          // default cleaning process
          $method = $this->getClearCacheMethod($options['type']);
          if (!method_exists($this, $method))
          {
            throw new InvalidArgumentException(sprintf('Do not know how to remove cache for type "%s".', $options['type']));
          }
          $this->$method($appConfiguration);
        }

        $this->unlock($app, $env);
      }
    }

    // clear global cache
    if (null === $options['app'] && 'all' == $options['type'])
    {
      $this->getFilesystem()->remove(sfFinder::type('file')->discard('.*')->in(sfConfig::get('sf_cache_dir')));
    }
  }

  protected function getClearCacheMethod($type)
  {
    return sprintf('clear%sCache', ucfirst($type));
  }

  protected function clearAllCache(sfApplicationConfiguration $appConfiguration)
  {
    $this->clearI18NCache($appConfiguration);
    $this->clearRoutingCache($appConfiguration);
    $this->clearTemplateCache($appConfiguration);
    $this->clearModuleCache($appConfiguration);
    $this->clearConfigCache($appConfiguration);
  }

  protected function clearConfigCache(sfApplicationConfiguration $appConfiguration)
  {
    $subDir = sfConfig::get('sf_cache_dir').'/'.$appConfiguration->getApplication().'/'.$appConfiguration->getEnvironment().'/config';

    if (is_dir($subDir))
    {
      // remove cache files
      $this->getFilesystem()->remove(sfFinder::type('file')->discard('.*')->in($subDir));
    }
  }

  protected function clearI18NCache(sfApplicationConfiguration $appConfiguration)
  {
    $config = $this->getFactoriesConfiguration($appConfiguration);

    if (isset($config['i18n']['param']['cache']))
    {
      $this->cleanCacheFromFactoryConfig($config['i18n']['param']['cache']);
    }
  }

  protected function clearRoutingCache(sfApplicationConfiguration $appConfiguration)
  {
    $config = $this->getFactoriesConfiguration($appConfiguration);

    if (isset($config['routing']['param']['cache']))
    {
      $this->cleanCacheFromFactoryConfig($config['routing']['param']['cache']);
    }
  }

  protected function clearTemplateCache(sfApplicationConfiguration $appConfiguration)
  {
    $config = $this->getFactoriesConfiguration($appConfiguration);

    if (isset($config['view_cache']))
    {
      $this->cleanCacheFromFactoryConfig($config['view_cache']);
    }
  }

  protected function clearModuleCache(sfApplicationConfiguration $appConfiguration)
  {
    $subDir = sfConfig::get('sf_cache_dir').'/'.$appConfiguration->getApplication().'/'.$appConfiguration->getEnvironment().'/modules';

    if (is_dir($subDir))
    {
      // remove cache files
      $this->getFilesystem()->remove(sfFinder::type('file')->discard('.*')->in($subDir));
    }
  }

  public function getFactoriesConfiguration(sfApplicationConfiguration $appConfiguration)
  {
    $app = $appConfiguration->getApplication();
    $env = $appConfiguration->getEnvironment();

    if (!isset($this->config[$app]))
    {
      $this->config[$app] = array();
    }

    if (!isset($this->config[$app][$env]))
    {
      $this->config[$app][$env] = sfFactoryConfigHandler::getConfiguration($appConfiguration->getConfigPaths('config/factories.yml'));
    }

    return $this->config[$app][$env] ;
  }

  public function cleanCacheFromFactoryConfig($class, $parameters = array())
  {
    if ($class)
    {
      // the standard array with ['class'] and ['param'] can be passed as well
      if (is_array($class))
      {
        if (!isset($class['class']))
        {
          return;
        }
        if (isset($class['param']))
        {
          $parameters = $class['param'];
        }
        $class = $class['class'];
      }
      try
      {
        $cache = new $class($parameters);
        $cache->clean();
      }
      catch (Exception $e)
      {
        $this->logSection('error', $e->getMessage(), 255, 'ERROR');
      }
    }
  }

  protected function lock($app, $env)
  {
    // create a lock file
    $this->getFilesystem()->touch($this->getLockFile($app, $env));

    // change mode so the web user can remove it if we die
    $this->getFilesystem()->chmod($this->getLockFile($app, $env), 0777);
  }

  protected function unlock($app, $env)
  {
    // release lock
    $this->getFilesystem()->remove($this->getLockFile($app, $env));
  }

  protected function getLockFile($app, $env)
  {
    return sfConfig::get('sf_data_dir').'/'.$app.'_'.$env.'-cli.lck';
  }
}
