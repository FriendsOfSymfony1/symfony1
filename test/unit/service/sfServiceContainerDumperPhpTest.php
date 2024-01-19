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

$t = new \lime_test(5);

$dir = __DIR__.'/fixtures/php';

// ->dump()
$t->diag('->dump()');
$dumper = new \sfServiceContainerDumperPhp($container = new \sfServiceContainerBuilder());

$t->is($dumper->dump(), file_get_contents($dir.'/services1.php'), '->dump() dumps an empty container as an empty PHP class');
$t->is($dumper->dump(['class' => 'Container', 'base_class' => 'AbstractContainer']), file_get_contents($dir.'/services1-1.php'), '->dump() takes a class and a base_class options');

$container = new \sfServiceContainerBuilder();
$dumper = new \sfServiceContainerDumperPhp($container);

// ->addParameters()
$t->diag('->addParameters()');
$container = include __DIR__.'/fixtures/containers/container8.php';
$dumper = new \sfServiceContainerDumperPhp($container);
$t->is($dumper->dump(), file_get_contents($dir.'/services8.php'), '->dump() dumps parameters');

// ->addService()
$t->diag('->addService()');
$container = include __DIR__.'/fixtures/containers/container9.php';
$dumper = new \sfServiceContainerDumperPhp($container);
$t->is($dumper->dump(), str_replace('%path%', __DIR__.'/fixtures/includes', file_get_contents($dir.'/services9.php')), '->dump() dumps services');

$dumper = new \sfServiceContainerDumperPhp($container = new \sfServiceContainerBuilder());
$container->register('foo', 'FooClass')->addArgument(new \stdClass());

try {
    $dumper->dump();
    $t->fail('->dump() throws a RuntimeException if the container to be dumped has reference to objects or resources');
} catch (\RuntimeException $e) {
    $t->pass('->dump() throws a RuntimeException if the container to be dumped has reference to objects or resources');
}
