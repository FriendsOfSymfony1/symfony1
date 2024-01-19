<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPluginConfiguration represents a configuration for a symfony plugin.
 *
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfPluginConfiguration
{
    protected $configuration;
    protected $dispatcher;
    protected $name;
    protected $rootDir;

    /**
     * Constructor.
     *
     * @param sfProjectConfiguration $configuration The project configuration
     * @param string                 $rootDir       The plugin root directory
     * @param string                 $name          The plugin name
     */
    public function __construct(sfProjectConfiguration $configuration, $rootDir = null, $name = null)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $configuration->getEventDispatcher();
        $this->rootDir = null === $rootDir ? $this->guessRootDir() : realpath($rootDir);
        $this->name = null === $name ? $this->guessName() : $name;

        $this->setup();
        $this->configure();

        if (!$this->configuration instanceof sfApplicationConfiguration) {
            $this->initializeAutoload();
            $this->initialize();
        }
    }

    /**
     * Sets up the plugin.
     *
     * This method can be used when creating a base plugin configuration class for other plugins to extend.
     */
    public function setup()
    {
    }

    /**
     * Configures the plugin.
     *
     * This method is called before the plugin's classes have been added to sfAutoload.
     */
    public function configure()
    {
    }

    /**
     * Initializes the plugin.
     *
     * This method is called after the plugin's classes have been added to sfAutoload.
     *
     * @return bool|null If false sfApplicationConfiguration will look for a config.php (maintains BC with symfony < 1.2)
     */
    public function initialize()
    {
    }

    /**
     * Returns the plugin root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Returns the plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Initializes autoloading for the plugin.
     *
     * This method is called when a plugin is initialized in a project
     * configuration. Otherwise, autoload is handled in
     * {@link sfApplicationConfiguration} using {@link sfAutoload}.
     *
     * @see sfSimpleAutoload
     */
    public function initializeAutoload()
    {
        $autoload = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');

        if (is_readable($file = $this->rootDir.'/config/autoload.yml')) {
            $this->configuration->getEventDispatcher()->connect('autoload.filter_config', [$this, 'filterAutoloadConfig']);
            $autoload->loadConfiguration([$file]);
            $this->configuration->getEventDispatcher()->disconnect('autoload.filter_config', [$this, 'filterAutoloadConfig']);
        } else {
            $autoload->addDirectory($this->rootDir.'/lib');
        }

        $autoload->register();
    }

    /**
     * Filters sfAutoload configuration values.
     *
     * @return array
     */
    public function filterAutoloadConfig(sfEvent $event, array $config)
    {
        // use array_merge so config is added to the front of the autoload array
        if (!isset($config['autoload'][$this->name.'_lib'])) {
            $config['autoload'] = array_merge([
                $this->name.'_lib' => [
                    'path' => $this->rootDir.'/lib',
                    'recursive' => true,
                ],
            ], $config['autoload']);
        }

        if (!isset($config['autoload'][$this->name.'_module_libs'])) {
            $config['autoload'] = array_merge([
                $this->name.'_module_libs' => [
                    'path' => $this->rootDir.'/modules/*/lib',
                    'recursive' => true,
                    'prefix' => 1,
                ],
            ], $config['autoload']);
        }

        return $config;
    }

    /**
     * Connects the current plugin's tests to the "test:*" tasks.
     */
    public function connectTests()
    {
        $this->dispatcher->connect('task.test.filter_test_files', [$this, 'filterTestFiles']);
    }

    /**
     * Listens for the "task.test.filter_test_files" event and adds tests from the current plugin.
     *
     * @param array $files
     *
     * @return array An array of files with the appropriate tests from the current plugin merged in
     */
    public function filterTestFiles(sfEvent $event, $files)
    {
        $task = $event->getSubject();

        if ($task instanceof sfTestAllTask) {
            $directory = $this->rootDir.'/test';
            $names = [];
        } elseif ($task instanceof sfTestFunctionalTask) {
            $directory = $this->rootDir.'/test/functional';
            $names = $event['arguments']['controller'];
        } elseif ($task instanceof sfTestUnitTask) {
            $directory = $this->rootDir.'/test/unit';
            $names = $event['arguments']['name'];
        }

        if (!count($names)) {
            $names = ['*'];
        }

        foreach ($names as $name) {
            $finder = sfFinder::type('file')->follow_link()->name(basename($name).'Test.php');
            $files = array_merge($files, $finder->in($directory.'/'.dirname($name)));
        }

        return array_unique($files);
    }

    /**
     * Guesses the plugin root directory.
     *
     * @return string
     */
    protected function guessRootDir()
    {
        $r = new ReflectionClass(get_class($this));

        return realpath(dirname($r->getFileName()).'/..');
    }

    /**
     * Guesses the plugin name.
     *
     * @return string
     */
    protected function guessName()
    {
        return substr(get_class($this), 0, -13);
    }
}
