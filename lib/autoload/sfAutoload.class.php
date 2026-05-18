<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @deprecated
 */
class sfAutoload
{
    protected static $freshCache = false;
    protected static $instance;

    protected $overriden = [];
    protected $classes = [];

    protected function __construct()
    {
    }

    /**
     * @deprecated
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new sfAutoload();
        }

        return self::$instance;
    }

    /**
     * @deprecated
     */
    public static function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');

        if (false === spl_autoload_register([self::getInstance(), 'autoload'])) {
            throw new sfException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
        }
    }

    /**
     * @deprecated
     */
    public static function unregister()
    {
        spl_autoload_unregister([self::getInstance(), 'autoload']);
    }

    /**
     * @deprecated
     */
    public function setClassPath($class, $path)
    {
        $class = strtolower($class);

        $this->overriden[$class] = $path;

        $this->classes[$class] = $path;
    }

    /**
     * @deprecated
     */
    public function getClassPath($class)
    {
        $class = strtolower($class);

        return isset($this->classes[$class]) ? $this->classes[$class] : null;
    }

    /**
     * @deprecated
     */
    public function reloadClasses($force = false)
    {
        // only (re)load the autoloading cache once per request
        if (self::$freshCache && !$force) {
            return false;
        }

        $configuration = sfProjectConfiguration::getActive();
        if (!$configuration || !$configuration instanceof sfApplicationConfiguration) {
            return false;
        }

        self::$freshCache = true;
        if (is_file($configuration->getConfigCache()->getCacheName('config/autoload.yml'))) {
            self::$freshCache = false;
            if ($force) {
                if (file_exists($configuration->getConfigCache()->getCacheName('config/autoload.yml'))) {
                    unlink($configuration->getConfigCache()->getCacheName('config/autoload.yml'));
                }
            }
        }

        $file = $configuration->getConfigCache()->checkConfig('config/autoload.yml');

        if ($force && defined('HHVM_VERSION')) {
            // workaround for https://github.com/facebook/hhvm/issues/1447
            $this->classes = eval(str_replace('<?php', '', file_get_contents($file)));
        } else {
            $this->classes = include $file;
        }

        foreach ($this->overriden as $class => $path) {
            $this->classes[$class] = $path;
        }

        return true;
    }

    /**
     * @deprecated
     */
    public function autoload($class)
    {
        // load the list of autoload classes
        if (!$this->classes) {
            self::reloadClasses();
        }

        return self::loadClass($class);
    }

    /**
     * @deprecated
     */
    public function loadClass($class)
    {
        $class = strtolower($class);

        // class already exists
        if (class_exists($class, false) || interface_exists($class, false) || (function_exists('trait_exists') && trait_exists($class, false))) {
            return true;
        }

        // we have a class path, let's include it
        if (isset($this->classes[$class])) {
            try {
                require $this->classes[$class];
            } catch (sfException $e) {
                $e->printStackTrace();
            } catch (Exception $e) {
                sfException::createFromException($e)->printStackTrace();
            }

            return true;
        }

        // see if the file exists in the current module lib directory
        if (
            sfContext::hasInstance()
            && ($module = sfContext::getInstance()->getModuleName())
            && isset($this->classes[$module.'/'.$class])
        ) {
            try {
                require $this->classes[$module.'/'.$class];
            } catch (sfException $e) {
                $e->printStackTrace();
            } catch (Exception $e) {
                sfException::createFromException($e)->printStackTrace();
            }

            return true;
        }

        return false;
    }
}
