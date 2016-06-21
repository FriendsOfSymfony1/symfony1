<?php

require_once __DIR__.'/../includes/classes.php';

$container = new sfServiceContainerBuilder();
$container->register('bar' , 'BarClass');
$container
    ->register('demo', 'ClassOptionalArguments')
    ->addArgument(new sfServiceReference('bar'))
    ->addArgument(new sfServiceReference('?bar'))
    ->addMethodCall('setOptionalRegisteredObject', array(new sfServiceReference('?bar')))
    ->addMethodCall('setRequiredRegisteredObject', array(new sfServiceReference('bar')))
    ->addMethodCall('setOptionalMissingObject', array(new sfServiceReference('?missing_bar')))
    ->addMethodCall('setRequiredMissingObject', array(new sfServiceReference('missing_bar')))
    ->setConfigurator(array(new sfServiceReference('?missing_bar'), 'configure'))
  ;

return $container;
