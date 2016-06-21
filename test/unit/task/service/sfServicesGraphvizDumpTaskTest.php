<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(__DIR__.'/../../../bootstrap/task.php');

$t = new lime_test(3);

$dispatcher = new sfEventDispatcher();
$formatter = new sfFormatter();

$task = new sfGenerateProjectTask($dispatcher, $formatter);
$task->run(array('test'));
$task = new sfGenerateAppTask($dispatcher, $formatter);
$task->run(array('frontend'));

require_once sfConfig::get('sf_root_dir').'/config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);

$dumpDir = sfConfig::get('sf_cache_dir') . '/service-container';
$dotFile = sprintf('%s/%d-graphviz.dot', $dumpDir, time());

$t->ok(! is_file($dotFile), 'Graphviz not yet exists');

$task = new sfServicesGraphvizDumpTask($dispatcher, $formatter);
$t->is($task->run(), 0, 'sfServicesGraphvizDumpTask task returns a correct return code');

if ($t->ok(is_file($dotFile), 'Graphviz created')) {
  unlink($dotFile);
}
