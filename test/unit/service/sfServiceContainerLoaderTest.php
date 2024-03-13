<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(11);

class ProjectLoader extends sfServiceContainerLoader
{
    public $container;

    public function doLoad($resource)
    {
        return $resource;
    }
}

// __construct()
$t->diag('__construct()');
$loader = new ProjectLoader($container = new sfServiceContainerBuilder());
$t->is($loader->container, $container, '__construct() takes a container builder instance as its first argument');

// ->setServiceContainer()
$t->diag('->setServiceContainer()');
$loader = new ProjectLoader();
$loader->setServiceContainer($container = new sfServiceContainerBuilder());
$t->is($loader->container, $container, '->setServiceContainer() sets the container builder attached to this loader');

// ->load()
$t->diag('->load()');
$loader = new ProjectLoader();

try {
    $loader->load('foo');
    $t->fail('->load() throws a LogicException if no container is attached to the loader');
} catch (LogicException $e) {
    $t->pass('->load() throws a LogicException if no container is attached to the loader');
}

$loader->setServiceContainer($container = new sfServiceContainerBuilder(['bar' => 'foo']));
$loader->load([[], ['foo' => 'bar']]);
$t->is($container->getParameters(), ['bar' => 'foo', 'foo' => 'bar'], '->load() merges current parameters with the loaded ones');

$loader->setServiceContainer($container = new sfServiceContainerBuilder(['bar' => 'foo', 'foo' => 'baz']));
$loader->load([[], ['foo' => 'bar']]);
$t->is($container->getParameters(), ['bar' => 'foo', 'foo' => 'baz'], '->load() does not change the already defined parameters');

$loader->setServiceContainer($container = new sfServiceContainerBuilder(['bar' => 'foo']));
$loader->load([[], ['foo' => '%bar%']]);
$t->is($container->getParameters(), ['bar' => 'foo', 'foo' => 'foo'], '->load() evaluates the values of the parameters towards already defined ones');

$loader->setServiceContainer($container = new sfServiceContainerBuilder(['bar' => 'foo']));
$loader->load([[], ['foo' => '%bar%', 'baz' => '%foo%']]);
$t->is($container->getParameters(), ['bar' => 'foo', 'foo' => 'foo', 'baz' => 'foo'], '->load() evaluates the values of the parameters towards already defined ones');

$loader->setServiceContainer($container = new sfServiceContainerBuilder());
$container->register('foo', 'FooClass');
$container->register('bar', 'BarClass');
$loader->load([['baz' => new sfServiceDefinition('BazClass'), 'alias_for_foo' => 'foo'], []]);
$t->is(array_keys($container->getServiceDefinitions()), ['foo', 'bar', 'baz'], '->load() merges definitions already defined ones');
$t->is($container->getAliases(), ['alias_for_foo' => 'foo'], '->load() registers defined aliases');

$loader->setServiceContainer($container = new sfServiceContainerBuilder());
$container->register('foo', 'FooClass');
$loader->load([['foo' => new sfServiceDefinition('BazClass')], []]);
$t->is($container->getServiceDefinition('foo')->getClass(), 'BazClass', '->load() overrides already defined services');

$loader->setServiceContainer($container = new sfServiceContainerBuilder());
$loader->load([[], ['foo' => 'bar']], [[], ['bar' => 'foo']]);
$t->is($container->getParameters(), ['foo' => 'bar', 'bar' => 'foo'], '->load() accepts several resources as argument');
