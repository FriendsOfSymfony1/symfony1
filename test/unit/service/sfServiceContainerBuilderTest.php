<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(48);

// ->setServiceDefinitions() ->addServiceDefinitions() ->getServiceDefinitions() ->setServiceDefinition() ->getServiceDefinition() ->hasServiceDefinition()
$t->diag('->setServiceDefinitions() ->addServiceDefinitions() ->getServiceDefinitions() ->setServiceDefinition() ->getServiceDefinition() ->hasServiceDefinition()');
$builder = new sfServiceContainerBuilder();
$definitions = [
    'foo' => new sfServiceDefinition('FooClass'),
    'bar' => new sfServiceDefinition('BarClass'),
];
$builder->setServiceDefinitions($definitions);
$t->is($builder->getServiceDefinitions(), $definitions, '->setServiceDefinitions() sets the service definitions');
$t->ok($builder->hasServiceDefinition('foo'), '->hasServiceDefinition() returns true if a service definition exists');
$t->ok(!$builder->hasServiceDefinition('foobar'), '->hasServiceDefinition() returns false if a service definition does not exist');

$builder->setServiceDefinition('foobar', $foo = new sfServiceDefinition('FooBarClass'));
$t->is($builder->getServiceDefinition('foobar'), $foo, '->getServiceDefinition() returns a service definition if defined');
$t->ok($builder->setServiceDefinition('foobar', $foo = new sfServiceDefinition('FooBarClass')) === $foo, '->setServiceDefinition() implements a fuild interface by returning the service reference');

$builder->addServiceDefinitions($defs = ['foobar' => new sfServiceDefinition('FooBarClass')]);
$t->is($builder->getServiceDefinitions(), array_merge($definitions, $defs), '->addServiceDefinitions() adds the service definitions');

try {
    $builder->getServiceDefinition('baz');
    $t->fail('->getServiceDefinition() throws an InvalidArgumentException if the service definition does not exist');
} catch (InvalidArgumentException $e) {
    $t->pass('->getServiceDefinition() throws an InvalidArgumentException if the service definition does not exist');
}

// ->register()
$t->diag('->register()');
$builder = new sfServiceContainerBuilder();
$builder->register('foo', 'FooClass');
$t->ok($builder->hasServiceDefinition('foo'), '->register() registers a new service definition');
$t->ok($builder->getServiceDefinition('foo') instanceof sfServiceDefinition, '->register() returns the newly created sfServiceDefinition instance');

// ->setAlias()
$t->diag('->setAlias()');
$builder = new sfServiceContainerBuilder();
$builder->register('foo', 'stdClass');
$builder->setAlias('bar', 'foo');
$t->ok($builder->hasService('bar'), '->setAlias() defines a new service');
$t->ok($builder->getService('bar') === $builder->getService('foo'), '->setAlias() creates a service that is an alias to another one');

// ->getAliases()
$t->diag('->getAliases()');
$builder = new sfServiceContainerBuilder();
$builder->setAlias('bar', 'foo');
$builder->setAlias('foobar', 'foo');
$t->is($builder->getAliases(), ['bar' => 'foo', 'foobar' => 'foo'], '->getAliases() returns all service aliases');
$builder->register('bar', 'stdClass');
$t->is($builder->getAliases(), ['foobar' => 'foo'], '->getAliases() does not return aliased services that have been overridden');
$builder->setService('foobar', 'stdClass');
$t->is($builder->getAliases(), [], '->getAliases() does not return aliased services that have been overridden');

// ->hasService()
$t->diag('->hasService()');
$builder = new sfServiceContainerBuilder();
$t->ok(!$builder->hasService('foo'), '->hasService() returns false if the service does not exist');
$builder->register('foo', 'FooClass');
$t->ok($builder->hasService('foo'), '->hasService() returns true if a service definition exists');
$builder->setAlias('foobar', 'foo');
$t->ok($builder->hasService('foo'), '->hasService() returns true if a service definition exists');
$builder->setService('bar', new stdClass());
$t->ok($builder->hasService('bar'), '->hasService() returns true if a service exists');
$builder->setAlias('foobar2', 'foo');
$t->ok($builder->hasService('foobar2'), '->hasService() returns true if a service exists');

// ->getService()
$t->diag('->getService()');
$builder = new sfServiceContainerBuilder();

try {
    $builder->getService('foo');
    $t->fail('->getService() throws an InvalidArgumentException if the service does not exist');
} catch (InvalidArgumentException $e) {
    $t->pass('->getService() throws an InvalidArgumentException if the service does not exist');
}
$builder->register('foo', 'stdClass');
$t->ok(is_object($builder->getService('foo')), '->getService() returns the service definition associated with the id');
$builder->setService('bar', $bar = new stdClass());
$t->is($builder->getService('bar'), $bar, '->getService() returns the service associated with the id');
$builder->register('bar', 'stdClass');
$t->is($builder->getService('bar'), $bar, '->getService() returns the service associated with the id even if a definition has been defined');

$builder->register('baz', 'stdClass')->setArguments([new sfServiceReference('baz')]);

try {
    @$builder->getService('baz');
    $t->fail('->getService() throws a LogicException if the service has a circular reference to itself');
} catch (LogicException $e) {
    $t->pass('->getService() throws a LogicException if the service has a circular reference to itself');
}

$builder->register('foobar', 'stdClass')->setShared(true);
$t->ok($builder->getService('bar') === $builder->getService('bar'), '->getService() always returns the same instance if the service is shared');

// ->getServiceIds()
$t->diag('->getServiceIds()');
$builder = new sfServiceContainerBuilder();
$builder->register('foo', 'stdClass');
$builder->bar = $bar = new stdClass();
$builder->register('bar', 'stdClass');
$t->is($builder->getServiceIds(), ['foo', 'bar', 'service_container'], '->getServiceIds() returns all defined service ids');

// ->createService() # file
$t->diag('->createService() # file');
$builder = new sfServiceContainerBuilder();
$builder->register('foo1', 'FooClass')->setFile(__DIR__.'/fixtures/includes/foo.php');
$t->ok($builder->getService('foo1'), '->createService() requires the file defined by the service definition');
$builder->register('foo2', 'FooClass')->setFile(__DIR__.'/fixtures/includes/%file%.php');
$builder->setParameter('file', 'foo');
$t->ok($builder->getService('foo2'), '->createService() replaces parameters in the file provided by the service definition');

// ->createService() # class
$t->diag('->createService() # class');
$builder = new sfServiceContainerBuilder();
$builder->register('foo1', '%class%');
$builder->setParameter('class', 'stdClass');
$t->ok($builder->getService('foo1') instanceof stdClass, '->createService() replaces parameters in the class provided by the service definition');

// ->createService() # arguments
$t->diag('->createService() # arguments');
$builder = new sfServiceContainerBuilder();
$builder->register('bar', 'stdClass');
$builder->register('foo1', 'FooClass')->addArgument(['foo' => '%value%', '%value%' => 'foo', new sfServiceReference('bar')]);
$builder->setParameter('value', 'bar');
$t->is($builder->getService('foo1')->arguments, ['foo' => 'bar', 'bar' => 'foo', $builder->getService('bar')], '->createService() replaces parameters and service references in the arguments provided by the service definition');

// ->createService() # constructor
$t->diag('->createService() # constructor');
$builder = new sfServiceContainerBuilder();
$builder->register('bar', 'stdClass');
$builder->register('foo1', 'FooClass')->setConstructor('getInstance')->addArgument(['foo' => '%value%', '%value%' => 'foo', new sfServiceReference('bar')]);
$builder->setParameter('value', 'bar');
$t->ok($builder->getService('foo1')->called, '->createService() calls the constructor to create the service instance');
$t->is($builder->getService('foo1')->arguments, ['foo' => 'bar', 'bar' => 'foo', $builder->getService('bar')], '->createService() passes the arguments to the constructor');

// ->createService() # method calls
$t->diag('->createService() # method calls');
$builder = new sfServiceContainerBuilder();
$builder->register('bar', 'stdClass');
$builder->register('foo1', 'FooClass')->addMethodCall('setBar', [['%value%', new sfServiceReference('bar')]]);
$builder->setParameter('value', 'bar');
$t->is($builder->getService('foo1')->bar, ['bar', $builder->getService('bar')], '->createService() replaces the values in the method calls arguments');

// ->createService() # configurator
require_once __DIR__.'/fixtures/includes/classes.php';
$t->diag('->createService() # configurator');
$builder = new sfServiceContainerBuilder();
$builder->register('foo1', 'FooClass')->setConfigurator('sc_configure');
$t->ok($builder->getService('foo1')->configured, '->createService() calls the configurator');

$builder->register('foo2', 'FooClass')->setConfigurator(['%class%', 'configureStatic']);
$builder->setParameter('class', 'BazClass');
$t->ok($builder->getService('foo2')->configured, '->createService() calls the configurator');

$builder->register('baz', 'BazClass');
$builder->register('foo3', 'FooClass')->setConfigurator([new sfServiceReference('baz'), 'configure']);
$t->ok($builder->getService('foo3')->configured, '->createService() calls the configurator');

$builder->register('foo4', 'FooClass')->setConfigurator('foo');

try {
    $builder->getService('foo4');
    $t->fail('->createService() throws an InvalidArgumentException if the configure callable is not a valid callable');
} catch (InvalidArgumentException $e) {
    $t->pass('->createService() throws an InvalidArgumentException if the configure callable is not a valid callable');
}

// ->resolveValue()
$t->diag('->resolveValue()');
$builder = new sfServiceContainerBuilder();
$t->is($builder->resolveValue('foo'), 'foo', '->resolveValue() returns its argument unmodified if no placeholders are found');
$builder->setParameter('foo', 'bar');
$t->is($builder->resolveValue('I\'m a %foo%'), 'I\'m a bar', '->resolveValue() replaces placeholders by their values');
$builder->setParameter('foo', true);
$t->ok(true === $builder->resolveValue('%foo%'), '->resolveValue() replaces arguments that are just a placeholder by their value without casting them to strings');

$builder->setParameter('foo', 'bar');
$t->is($builder->resolveValue(['%foo%' => '%foo%']), ['bar' => 'bar'], '->resolveValue() replaces placeholders in keys and values of arrays');

$t->is($builder->resolveValue(['%foo%' => ['%foo%' => ['%foo%' => '%foo%']]]), ['bar' => ['bar' => ['bar' => 'bar']]], '->resolveValue() replaces placeholders in nested arrays');

$t->is($builder->resolveValue('I\'m a %%foo%%'), 'I\'m a %foo%', '->resolveValue() supports % escaping by doubling it');
$t->is($builder->resolveValue('I\'m a %foo% %%foo %foo%'), 'I\'m a bar %foo bar', '->resolveValue() supports % escaping by doubling it');

try {
    $builder->resolveValue('%foobar%');
    $t->fail('->resolveValue() throws a InvalidArgumentException if a placeholder references a non-existant parameter');
} catch (InvalidArgumentException $e) {
    $t->pass('->resolveValue() throws a InvalidArgumentException if a placeholder references a non-existant parameter');
}

try {
    $builder->resolveValue('foo %foobar% bar');
    $t->fail('->resolveValue() throws a InvalidArgumentException if a placeholder references a non-existant parameter');
} catch (InvalidArgumentException $e) {
    $t->pass('->resolveValue() throws a InvalidArgumentException if a placeholder references a non-existant parameter');
}

// ->resolveServices()
$t->diag('->resolveServices()');
$builder = new sfServiceContainerBuilder();
$builder->register('foo', 'FooClass');
$t->is($builder->resolveServices(new sfServiceReference('foo')), $builder->getService('foo'), '->resolveServices() resolves service references to service instances');
$t->is($builder->resolveServices(['foo' => ['foo', new sfServiceReference('foo')]]), ['foo' => ['foo', $builder->getService('foo')]], '->resolveServices() resolves service references to service instances in nested arrays');
