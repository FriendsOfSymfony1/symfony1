<?php

$container = new sfServiceContainerBuilder();
$container->setParameters([
    'FOO' => 'bar',
    'bar' => 'foo is %foo bar',
    'values' => [true, false, null, 0, 1000.3, 'true', 'false', 'null'],
]);

return $container;
