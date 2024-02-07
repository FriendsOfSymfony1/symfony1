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

\sfConfig::set('sf_symfony_lib_dir', realpath(__DIR__.'/../../../lib'));

$t = new \lime_test(1);

// prefix
$handler = new \sfDefineEnvironmentConfigHandler();
$handler->initialize(['prefix' => 'sf_']);

$dir = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'sfDefineEnvironmentConfigHandler'.DIRECTORY_SEPARATOR;

$files = [
    $dir.'prefix_default.yml',
    $dir.'prefix_all.yml',
];

\sfConfig::set('sf_environment', 'prod');

$data = $handler->execute($files);
$data = preg_replace('#date\: \d+/\d+/\d+ \d+\:\d+\:\d+#', '', $data);

$t->is($data, str_replace("\r\n", "\n", file_get_contents($dir.'prefix_result.php')));
