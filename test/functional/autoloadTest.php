<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
if (!include __DIR__.'/../bootstrap/functional.php') {
    return;
}

$b = new \sfTestBrowser();

$b->
  get('/autoload/myAutoload')->
  with('request')->begin()->
    isParameter('module', 'autoload')->
    isParameter('action', 'myAutoload')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body div', 'foo')->
  end();

$t = $b->test();

$t->ok(class_exists('BaseExtendMe'), 'plugin lib directory added to autoload');
$r = new \ReflectionClass('ExtendMe');
$t->like(str_replace(DIRECTORY_SEPARATOR, '/', $r->getFilename()), '~fixtures/lib/ExtendMe~', 'plugin class can be replaced by project');
$t->ok(class_exists('NotInLib'), 'plugin autoload sets class paths');
$t->ok(!class_exists('ExcludedFromAutoload'), 'plugin autoload excludes directories');
