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

$t = new \lime_test(6);

$dispatcher = new \sfEventDispatcher();

require_once __DIR__.'/../../../lib/util/sfToolkit.class.php';
$file = sys_get_temp_dir().DIRECTORY_SEPARATOR.'sf_log_file.txt';
if (file_exists($file)) {
    unlink($file);
}
$fileLogger = new \sfFileLogger($dispatcher, ['file' => $file]);
$buffer = fopen('php://memory', 'rw');
$streamLogger = new \sfStreamLogger($dispatcher, ['stream' => $buffer]);

// ->initialize()
$t->diag('->initialize()');
$logger = new \sfAggregateLogger($dispatcher, ['loggers' => $fileLogger]);
$t->is($logger->getLoggers(), [$fileLogger], '->initialize() can take a "loggers" parameter');

$logger = new \sfAggregateLogger($dispatcher, ['loggers' => [$fileLogger, $streamLogger]]);
$t->is($logger->getLoggers(), [$fileLogger, $streamLogger], '->initialize() can take a "loggers" parameter');

// ->log()
$t->diag('->log()');
$logger->log('foo');
rewind($buffer);
$content = stream_get_contents($buffer);
$lines = explode("\n", file_get_contents($file));
$t->like($lines[0], '/foo/', '->log() logs a message to all loggers');
$t->is($content, 'foo'.PHP_EOL, '->log() logs a message to all loggers');

// ->getLoggers() ->addLoggers() ->addLogger()
$logger = new \sfAggregateLogger($dispatcher);
$logger->addLogger($fileLogger);
$t->is($logger->getLoggers(), [$fileLogger], '->addLogger() adds a new sfLogger instance');

$logger = new \sfAggregateLogger($dispatcher);
$logger->addLoggers([$fileLogger, $streamLogger]);
$t->is($logger->getLoggers(), [$fileLogger, $streamLogger], '->addLoggers() adds an array of sfLogger instances');

// ->shutdown()
$t->diag('->shutdown()');
$logger->shutdown();

unlink($file);
