<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include dirname(__FILE__).'/../../bootstrap/unit.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php';

class TestTask extends sfBaseTask
{
  protected function execute($arguments = array(), $options = array())
  {
  }

  public function reloadAutoload()
  {
    parent::reloadAutoload();
  }

  public function initializeAutoload(sfProjectConfiguration $configuration, $reload = false)
  {
    parent::initializeAutoload($configuration, $reload);
  }
}

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->setPlugins(array('sfAutoloadPlugin'));
  }
}

$rootDir = dirname(__FILE__).'/../../functional/fixtures';
sfToolkit::clearDirectory($rootDir.'/cache');

$dispatcher = new sfEventDispatcher();
$configuration = new ProjectConfiguration($rootDir, $dispatcher);
$autoload = sfSimpleAutoload::getInstance();

$t = new lime_test(5);
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
