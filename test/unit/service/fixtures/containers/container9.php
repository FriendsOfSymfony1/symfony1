<?php

require_once __DIR__.'/../includes/classes.php';

$container = new sfServiceContainerBuilder();
$container->
  register('foo', 'FooClass')->
  setConstructor('getInstance')->
  setArguments(['foo', new sfServiceReference('foo.baz'), ['%foo%' => 'foo is %foo%'], true, new sfServiceReference('service_container')])->
  setFile(realpath(__DIR__.'/../includes/foo.php'))->
  setShared(false)->
  addMethodCall('setBar', ['bar'])->
  addMethodCall('initialize')->
  setConfigurator('sc_configure');
$container->
  register('bar', 'FooClass')->
  setArguments(['foo', new sfServiceReference('foo.baz'), new sfServiceParameter('foo_bar')])->
  setShared(true)->
  setConfigurator([new sfServiceReference('foo.baz'), 'configure']);
$container->
  register('foo.baz', '%baz_class%')->
  setConstructor('getInstance')->
  setConfigurator(['%baz_class%', 'configureStatic1']);
$container->register('foo_bar', 'FooClass');
$container->setParameters([
    'baz_class' => 'BazClass',
    'foo' => 'bar',
    'foo_bar' => new sfServiceReference('foo_bar'),
]);
$container->setAlias('alias_for_foo', 'foo');

return $container;
