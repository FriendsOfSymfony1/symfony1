<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSimpleAutoload class.
 *
 * This class is a singleton as PHP seems to be unable to register 2 autoloaders that are instances
 * of the same class (why?).
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfSimpleAutoload
{
    protected static $registered = false;
    protected static $instance;

    protected $cacheFile;
    protected $cacheLoaded = false;
    protected $cacheChanged = false;
    protected $dirs = [];
    protected $files = [];
    protected $classes = [];
    protected $overriden = [];

    protected function __construct($cacheFile = null)
    {
        if (null !== $cacheFile) {
            $this->cacheFile = $cacheFile;
        }

        $this->loadCache();
    }

    /**
     * Retrieves the singleton instance of this class.
     *
     * @param string $cacheFile The file path to save the cache
     *
     * @return \sfSimpleAutoload a sfSimpleAutoload implementation instance
     */
    public static function getInstance($cacheFile = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new \sfSimpleAutoload($cacheFile);
        }

        return self::$instance;
    }

    /**
     * Register sfSimpleAutoload in spl autoloader.
     *
     * @throws \sfException
     */
    public static function register()
    {
        if (self::$registered) {
            return;
        }

        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register([self::getInstance(), 'autoload'])) {
            throw new \sfException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
        }

        if (self::getInstance()->cacheFile) {
            register_shutdown_function([self::getInstance(), 'saveCache']);
        }

        self::$registered = true;
    }

    /**
     * Unregister sfSimpleAutoload from spl autoloader.
     */
    public static function unregister()
    {
        spl_autoload_unregister([self::getInstance(), 'autoload']);
        self::$registered = false;
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class a class name
     *
     * @return bool Returns true if the class has been loaded
     */
    public function autoload($class)
    {
        $class = strtolower($class);

        // class already exists
        if (class_exists($class, false) || interface_exists($class, false)) {
            return true;
        }

        // we have a class path, let's include it
        if (isset($this->classes[$class])) {
            try {
                require $this->classes[$class];
            } catch (\sfException $e) {
                $e->printStackTrace();
            } catch (\Exception $e) {
                \sfException::createFromException($e)->printStackTrace();
            }

            return true;
        }

        return false;
    }

    /**
     * Loads the cache.
     */
    public function loadCache()
    {
        if (!$this->cacheFile || !is_readable($this->cacheFile)) {
            return;
        }

        list($this->classes, $this->dirs, $this->files) = unserialize(file_get_contents($this->cacheFile));

        $this->cacheLoaded = true;
        $this->cacheChanged = false;
    }

    /**
     * Saves the cache.
     */
    public function saveCache()
    {
        if ($this->cacheChanged) {
            if (is_writable(dirname($this->cacheFile))) {
                file_put_contents($this->cacheFile, serialize([$this->classes, $this->dirs, $this->files]));
            }

            $this->cacheChanged = false;
        }
    }

    /**
     * Reloads cache.
     */
    public function reload()
    {
        $this->classes = [];
        $this->cacheLoaded = false;

        foreach ($this->dirs as $dir) {
            $this->addDirectory($dir);
        }

        foreach ($this->files as $file) {
            $this->addFile($file);
        }

        foreach ($this->overriden as $class => $path) {
            $this->classes[$class] = $path;
        }

        $this->cacheLoaded = true;
        $this->cacheChanged = true;
    }

    /**
     * Removes the cache.
     */
    public function removeCache()
    {
        @unlink($this->cacheFile);
    }

    /**
     * Adds a directory to the autoloading system if not yet present and give it the highest possible precedence.
     *
     * @param string $dir The directory to look for classes
     * @param string $ext The extension to look for
     */
    public function addDirectory($dir, $ext = '.php')
    {
        $finder = \sfFinder::type('file')->follow_link()->name('*'.$ext);

        if ($dirs = glob($dir)) {
            foreach ($dirs as $dir) {
                if (false !== $key = array_search($dir, $this->dirs)) {
                    unset($this->dirs[$key]);
                    $this->dirs[] = $dir;

                    if ($this->cacheLoaded) {
                        continue;
                    }
                } else {
                    $this->dirs[] = $dir;
                }

                $this->cacheChanged = true;
                $this->addFiles($finder->in($dir), false);
            }
        }
    }

    /**
     * Adds files to the autoloading system.
     *
     * @param array $files    An array of files
     * @param bool  $register Whether to register those files as single entities (used when reloading)
     */
    public function addFiles(array $files, $register = true)
    {
        foreach ($files as $file) {
            $this->addFile($file, $register);
        }
    }

    /**
     * Adds a file to the autoloading system.
     *
     * @param string $file     A file path
     * @param bool   $register Whether to register those files as single entities (used when reloading)
     */
    public function addFile($file, $register = true)
    {
        if (!is_file($file)) {
            return;
        }

        if (in_array($file, $this->files)) {
            if ($this->cacheLoaded) {
                return;
            }
        } else {
            if ($register) {
                $this->files[] = $file;
            }
        }

        if ($register) {
            $this->cacheChanged = true;
        }

        preg_match_all('~^\s*(?:abstract\s+|final\s+)?(?:class|interface|trait)\s+(\w+)~mi', file_get_contents($file), $classes);
        foreach ($classes[1] as $class) {
            $this->classes[strtolower($class)] = $file;
        }
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
     * @return \string|null An absolute path
     */
    public function getClassPath($class)
    {
        $class = strtolower($class);

        return isset($this->classes[$class]) ? $this->classes[$class] : null;
    }

    /**
     * Loads configuration from the supplied files.
     *
     * @param array $files An array of autoload.yml files
     *
     * @see \sfAutoloadConfigHandler
     */
    public function loadConfiguration(array $files)
    {
        $config = new \sfAutoloadConfigHandler();
        foreach ($config->evaluate($files) as $class => $file) {
            $this->setClassPath($class, $file);
        }
    }
}
