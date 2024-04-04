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
     * @deprecated
     */
    public static function getInstance($cacheFile = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new sfSimpleAutoload($cacheFile);
        }

        return self::$instance;
    }

    /**
     * @deprecated
     */
    public static function register()
    {
        if (self::$registered) {
            return;
        }

        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register([self::getInstance(), 'autoload'])) {
            throw new sfException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
        }

        if (self::getInstance()->cacheFile) {
            register_shutdown_function([self::getInstance(), 'saveCache']);
        }

        self::$registered = true;
    }

    /**
     * @deprecated
     */
    public static function unregister()
    {
        spl_autoload_unregister([self::getInstance(), 'autoload']);
        self::$registered = false;
    }

    /**
     * @deprecated
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
            } catch (sfException $e) {
                $e->printStackTrace();
            } catch (Exception $e) {
                sfException::createFromException($e)->printStackTrace();
            }

            return true;
        }

        return false;
    }

    /**
     * @deprecated
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
     * @deprecated
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
     * @deprecated
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
     * @deprecated
     */
    public function removeCache()
    {
        @unlink($this->cacheFile);
    }

    /**
     * @deprecated
     */
    public function addDirectory($dir, $ext = '.php')
    {
        $finder = sfFinder::type('file')->follow_link()->name('*'.$ext);

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
     * @deprecated
     */
    public function addFiles(array $files, $register = true)
    {
        foreach ($files as $file) {
            $this->addFile($file, $register);
        }
    }

    /**
     * @deprecated
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
    public function loadConfiguration(array $files)
    {
        $config = new sfAutoloadConfigHandler();
        foreach ($config->evaluate($files) as $class => $file) {
            $this->setClassPath($class, $file);
        }
    }
}
