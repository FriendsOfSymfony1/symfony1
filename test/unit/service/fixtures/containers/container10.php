<?php

require_once __DIR__.'/../includes/classes.php';

$container = new sfServiceContainerBuilder();
$container->
  register('foo', 'FooClass')->
  addArgument(new sfServiceReference('bar'))
;

return $container;
