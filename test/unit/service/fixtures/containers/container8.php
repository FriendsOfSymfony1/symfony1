<?php

$container = new sfServiceContainerBuilder();
$container->setParameters(array(
  'FOO'    => 'bar',
  'bar'    => 'foo is %foo bar',
  'values' => array(true, false, null, 0, 1000.3, 'true', 'false', 'null'),
));

return $container;
