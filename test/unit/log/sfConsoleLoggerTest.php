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

$t = new \lime_test(1);

$logger = new \sfConsoleLogger(new \sfEventDispatcher());
$logger->setStream($buffer = fopen('php://memory', 'rw'));

$logger->log('foo');
rewind($buffer);
$t->is(fix_linebreaks(stream_get_contents($buffer)), "foo\n", 'sfConsoleLogger logs messages to the console');
