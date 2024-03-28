<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include __DIR__.'/../../bootstrap/unit.php';

require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php';

class TestTask extends sfBaseTask
{
    public function reloadAutoload()
    {
        parent::reloadAutoload();
    }

    public function initializeAutoload(sfProjectConfiguration $configuration, $reload = false)
    {
        parent::initializeAutoload($configuration, $reload);
    }

    protected function execute($arguments = [], $options = [])
    {
    }
}

$rootDir = __DIR__.'/../../functional/fixtures';
sfToolkit::clearDirectory($rootDir.'/cache');

$dispatcher = new sfEventDispatcher();

require_once $rootDir.'/config/ProjectConfiguration.class.php';
$configuration = new ProjectConfiguration($rootDir, $dispatcher);
$autoload = sfSimpleAutoload::getInstance();

$t = new lime_test(16);
$task = new TestTask($dispatcher, new sfFormatter());

// ->initializeAutoload()
$t->diag('->initializeAutoload()');

$t->is($autoload->getClassPath('myLibClass'), null, 'no project classes are autoloaded before ->initializeAutoload()');

$task->initializeAutoload($configuration);

$t->ok(null !== $autoload->getClassPath('myLibClass'), '->initializeAutoload() loads project classes');
$t->ok(null !== $autoload->getClassPath('BaseExtendMe'), '->initializeAutoload() includes plugin classes');
$t->is($autoload->getClassPath('ExtendMe'), sfConfig::get('sf_lib_dir').'/ExtendMe.class.php', '->initializeAutoload() prefers project to plugin classes');

$task->initializeAutoload($configuration, true);
$t->is($autoload->getClassPath('ExtendMe'), sfConfig::get('sf_lib_dir').'/ExtendMe.class.php', '->initializeAutoload() prefers project to plugin classes after reload');

// ->run()
$t->diag('->run()');

class ApplicationTask extends sfBaseTask
{
    public function getServiceContainer()
    {
        return parent::getServiceContainer();
    }

    public function getRouting()
    {
        return parent::getRouting();
    }

    public function getMailer()
    {
        return parent::getMailer();
    }

    protected function configure()
    {
        $this->addOption('application', null, sfCommandOption::PARAMETER_REQUIRED, '', true);
    }

    protected function execute($arguments = [], $options = [])
    {
        if (!$this->configuration instanceof sfApplicationConfiguration) {
            throw new Exception('This task requires an application configuration be loaded.');
        }
    }
}

chdir($rootDir);

$task = new ApplicationTask($dispatcher, new sfFormatter());

try {
    $task->run();
    $t->pass('->run() creates an application configuration if none is set');
} catch (Exception $e) {
    $t->diag($e->getMessage());
    $t->fail('->run() creates an application configuration if none is set');
}

$task = new ApplicationTask($dispatcher, new sfFormatter());
$task->setConfiguration($configuration);

try {
    $task->run();
    $t->pass('->run() creates an application configuration if only a project configuration is set');
} catch (Exception $e) {
    $t->diag($e->getMessage());
    $t->fail('->run() creates an application configuration if only a project configuration is set');
}

// ->getServiceContainer()
$t->diag('->getServiceContainer()');
$serviceContainer = $task->getServiceContainer();

$t->ok($serviceContainer instanceof sfServiceContainer, '->getServiceContainer() returns an sfServiceContainer');
$t->is($serviceContainer, $task->getServiceContainer(), '->getServiceContainer() returns always the same instance');
$t->ok($serviceContainer->hasService('my_project_service'), '->getServiceContainer() is correctly configured');

// ->getRouting()
$t->diag('->getRouting()');
$routing = $task->getRouting();

$t->ok($routing instanceof sfRouting, '->getRouting() returns an sfPatternRouting');
$t->is($routing, $task->getRouting(), '->getRouting() returns always the same instance');
$t->ok($routing->hasRouteName('homepage'), '->getRouting() is correctly configured');

// ->getMailer()
$t->diag('->getMailer()');
$mailer = $task->getMailer();

$t->ok($mailer instanceof sfMailer, '->getMailer() returns an sfMailer');
$t->is($mailer, $task->getMailer(), '->getMailer() returns always the same instance');
$t->is($mailer->getDeliveryStrategy(), sfMailer::REALTIME, '->getMailer() is correctly configured');
