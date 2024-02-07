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
 * Autoload again for dev environments.
 *
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfAutoloadAgain
{
    protected static $instance;

    protected $registered = false;
    protected $reloaded = false;

    /**
     * Constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Returns the singleton autoloader.
     *
     * @return \sfAutoloadAgain
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Reloads the autoloader.
     *
     * @param string $class
     *
     * @return bool
     */
    public function autoload($class)
    {
        // only reload once
        if ($this->reloaded) {
            return false;
        }

        $autoloads = spl_autoload_functions();

        // as of PHP 5.2.11, spl_autoload_functions() returns the object as the first element of the array instead of the class name
        if (version_compare(PHP_VERSION, '5.2.11', '>=')) {
            foreach ($autoloads as $position => $autoload) {
                if (is_array($autoload) && $this === $autoload[0]) {
                    break;
                }
            }
        } else {
            $position = array_search([__CLASS__, 'autoload'], $autoloads, true);
        }

        if (isset($autoloads[$position + 1])) {
            $this->unregister();
            $this->register();

            // since we're rearranged things, call the chain again
            spl_autoload_call($class);

            return class_exists($class, false) || interface_exists($class, false);
        }

        $autoload = \sfAutoload::getInstance();
        $autoload->reloadClasses(true);

        $this->reloaded = true;

        return $autoload->autoload($class);
    }

    /**
     * Returns true if the autoloader is registered.
     *
     * @return bool
     */
    public function isRegistered()
    {
        return $this->registered;
    }

    /**
     * Registers the autoloader function.
     */
    public function register()
    {
        if (!$this->isRegistered()) {
            spl_autoload_register([$this, 'autoload']);
            $this->registered = true;
        }
    }

    /**
     * Unregisters the autoloader function.
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'autoload']);
        $this->registered = false;
    }
}
