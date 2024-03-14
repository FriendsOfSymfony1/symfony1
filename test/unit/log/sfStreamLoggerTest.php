<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(1);

$dispatcher = new sfEventDispatcher();

$buffer = fopen('php://memory', 'rw');
$logger = new sfStreamLogger($dispatcher, ['stream' => $buffer]);

$logger->log('foo');
rewind($buffer);
$t->is(fix_linebreaks(stream_get_contents($buffer)), "foo\n", 'sfStreamLogger logs messages to a PHP stream');
