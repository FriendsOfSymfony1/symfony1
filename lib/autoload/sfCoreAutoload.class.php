<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// The current symfony version.

/**
 * @deprecated
 */
class sfCoreAutoload
{
    protected static $registered = false;
    protected static $instance;

    protected $baseDir;

    // Don't edit this property by hand.
    // To update it, use sfCoreAutoload::make()
    protected $classes = [
    ];

    protected function __construct()
    {
        $this->baseDir = realpath(__DIR__.'/..');
    }

    /**
     * @deprecated
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new sfCoreAutoload();
        }

        return self::$instance;
    }

    /**
     * @deprecated
     */
    public static function register()
    {
        self::$registered = true;

        if (self::$registered) {
            return;
        }

        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register([self::getInstance(), 'autoload'])) {
            throw new sfException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
        }

        self::$registered = true;
    }

    /**
     * @deprecated
     */
    public static function unregister()
    {
        // spl_autoload_unregister([self::getInstance(), 'autoload']);
        self::$registered = false;
    }

    /**
     * @deprecated
     */
    public function autoload($class)
    {
        if ($path = $this->getClassPath($class)) {
            require $path;

            return true;
        }

        return false;
    }

    /**
     * @deprecated
     */
    public function getClassPath($class)
    {
        $class = strtolower($class);

        if (!isset($this->classes[$class])) {
            return null;
        }

        return $this->baseDir.'/'.$this->classes[$class];
    }

    /**
     * @deprecated
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @deprecated
     */
    public static function make()
    {
        $libDir = str_replace(DIRECTORY_SEPARATOR, '/', realpath(__DIR__.DIRECTORY_SEPARATOR.'..'));

        require_once $libDir.'/util/sfFinder.class.php';

        $files = sfFinder::type('file')
            ->prune('plugins')
            ->prune('vendor')
            ->prune('skeleton')
            ->prune('default')
            ->prune('helper')
            ->name('*.php')
            ->in($libDir)
        ;

        sort($files, SORT_STRING);

        $classes = '';
        foreach ($files as $file) {
            $file = str_replace(DIRECTORY_SEPARATOR, '/', $file);
            $class = basename($file, false === strpos($file, '.class.php') ? '.php' : '.class.php');

            $contents = file_get_contents($file);
            if (false !== stripos($contents, 'class '.$class)
                || false !== stripos($contents, 'interface '.$class)
                || false !== stripos($contents, 'trait '.$class)) {
                $classes .= sprintf("    '%s' => '%s',\n", strtolower($class), substr(str_replace($libDir, '', $file), 1));
            }
        }

        $content = preg_replace('/protected \$classes = array *\(.*?\);/s', sprintf("protected \$classes = array(\n%s  );", $classes), file_get_contents(__FILE__));

        file_put_contents(__FILE__, $content);
    }
}
