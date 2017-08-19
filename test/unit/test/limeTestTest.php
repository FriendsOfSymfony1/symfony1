<?php

require_once(__DIR__.'/../../bootstrap/unit.php');

$t = new lime_test(23);

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

$t->comment('C. Equivalent number <-> strings');
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
$t->isnt(100, array(100));

$t->comment('E. Equivalent falsy');
$t->is(0, false);
$t->is(0, null);
$t->is('', 0);
$t->is(false, null);
$t->is(array(), null);
