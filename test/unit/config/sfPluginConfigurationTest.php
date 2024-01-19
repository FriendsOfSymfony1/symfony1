<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$rootDir = realpath(__DIR__.'/../../functional/fixtures');
$pluginRoot = realpath($rootDir.'/plugins/sfAutoloadPlugin');

require_once $pluginRoot.'/config/sfAutoloadPluginConfiguration.class.php';

$t = new \lime_test(9);

class ProjectConfiguration extends \sfProjectConfiguration
{
    public function setup()
    {
        $this->enablePlugins('sfAutoloadPlugin');
    }
}

// ->guessRootDir() ->guessName()
$t->diag('->guessRootDir() ->guessName()');

$configuration = new \sfProjectConfiguration($rootDir);
$pluginConfig = new \sfAutoloadPluginConfiguration($configuration);

$t->is($pluginConfig->getRootDir(), $pluginRoot, '->guessRootDir() guesses plugin root directory');
$t->is($pluginConfig->getName(), 'sfAutoloadPlugin', '->guessName() guesses plugin name');

// ->filterTestFiles()
$t->diag('->filterTestFiles()');

// test:all
$task = new \sfTestAllTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => [], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 6, '->filterTestFiles() adds all plugin tests');

// test:functional
$task = new \sfTestFunctionalTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['controller' => []], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 3, '->filterTestFiles() adds functional plugin tests');

$task = new \sfTestFunctionalTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['controller' => ['BarFunctional']], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 1, '->filterTestFiles() adds functional plugin tests when a controller is specified');

$task = new \sfTestFunctionalTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['controller' => ['nested/NestedFunctional']], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 1, '->filterTestFiles() adds functional plugin tests when a nested controller is specified');

// test:unit
$task = new \sfTestUnitTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['name' => []], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 3, '->filterTestFiles() adds unit plugin tests');

$task = new \sfTestUnitTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['name' => ['FooUnit']], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 1, '->filterTestFiles() adds unit plugin tests when a name is specified');

$task = new \sfTestUnitTask($configuration->getEventDispatcher(), new \sfFormatter());
$event = new \sfEvent($task, 'task.test.filter_test_files', ['arguments' => ['name' => ['nested/NestedUnit']], 'options' => []]);
$files = $pluginConfig->filterTestFiles($event, []);
$t->is(count($files), 1, '->filterTestFiles() adds unit plugin tests when a nested name is specified');
