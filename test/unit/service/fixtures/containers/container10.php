<?php

require_once dirname(__FILE__).'/../includes/classes.php';

$container = new sfServiceContainerBuilder();
$container->
  register('foo', 'FooClass')->
  addArgument(new sfServiceReference('bar'))
;

return $container;
