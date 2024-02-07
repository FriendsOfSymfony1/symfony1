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

$t = new \lime_test(28);

$t->comment('A. Equal numbers');
$t->is(1, 1);
$t->is(2, 2);
$t->is(-100, -100);
$t->is(0, 0);

$t->comment('B. Equal strings');
$t->is('', '');
$t->is('A', 'A');
$t->is('aaa', 'aaa');
$t->is("\0", "\0");

$t->comment('C. Equivalent number <-> numeric string');
$t->is('0', 0);
$t->is('1', 1);
$t->is('-1', -1);
$t->is('10000000.0', 10000000.0);

$t->comment('D. Not equal numbers');
$t->isnt(10, 1);
$t->isnt(-2, 2);
$t->isnt(100, 100.1);
$t->isnt(0, -1);
$t->isnt(-2, 'Hello');
$t->isnt(100, [100]);

$t->comment('E. Both falsy');
$t->is(0, false);
$t->is(0, null);
$t->is('', false);
$t->is(false, null);
$t->is([], null);

$t->comment('F. Values that should not be equal');
$t->isnt(true, 'Hello');
$t->isnt('Hello', true);
$t->isnt('Hello', 0);
$t->isnt(0, 'Hello');
$t->isnt('', 0);
