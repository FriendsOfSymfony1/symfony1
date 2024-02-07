<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../includes/classes.php';

$container = new \sfServiceContainerBuilder();
$container->
  register('foo', 'FooClass')->
  addArgument(new \sfServiceReference('bar'));

return $container;
