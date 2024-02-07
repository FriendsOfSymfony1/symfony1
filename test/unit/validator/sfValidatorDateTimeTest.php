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

$t = new \lime_test(4);

$v = new \sfValidatorDateTime();

$t->ok($v instanceof \sfValidatorDate, 'sfValidatorDateTime extends sfValidatorDate');

// with_time option
$t->diag('with_time option');
$t->is($v->clean(time()), date('Y-m-d H:i:s', time()), '->clean() validates date with time');
$t->is($v->clean(['year' => 2005, 'month' => 1, 'day' => 4, 'hour' => 2, 'minute' => 23, 'second' => 33]), '2005-01-04 02:23:33', '->clean() validates date with time');
$t->is($v->clean('1855-08-25 13:22:56'), '1855-08-25 13:22:56', '->clean() validates date with time');
