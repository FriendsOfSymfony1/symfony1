<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(4);

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array('sfAutoloadPlugin', 'sfConfigPlugin'));
    $this->setPluginPath('sfConfigPlugin', $this->rootDir.'/lib/plugins/sfConfigPlugin');
  }
}

$configuration = new ProjectConfiguration(dirname(__FILE__).'/../../functional/fixtures');

$t->diag('->setPlugins(), ->disablePlugins(), ->enableAllPluginsExcept()');
foreach (array('setPlugins', 'disablePlugins', 'enableAllPluginsExcept') as $method)
{
  try
  {
    $configuration->$method(array());
    $t->fail('->'.$method.'() throws an exception if called too late');
  }
  catch (Exception $e)
  {
    $t->pass('->'.$method.'() throws an exception if called too late');
  }
}

class ProjectConfiguration2 extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfAutoloadPlugin', 'sfConfigPlugin');
  }
}

$configuration2 = new ProjectConfiguration2(dirname(__FILE__).'/../../functional/fixtures');
$t->is_deeply($configuration2->getPlugins(), array('sfAutoloadPlugin', 'sfConfigPlugin'), '->enablePlugins() can enable plugins passed as arguments instead of array');