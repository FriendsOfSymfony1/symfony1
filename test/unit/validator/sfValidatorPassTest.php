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

$t = new \lime_test(2);

$v = new \sfValidatorPass();

// ->clean()
$t->diag('->clean()');
$t->is($v->clean(''), '', '->clean() always returns the value unmodified');
$t->is($v->clean(null), null, '->clean() always returns the value unmodified');
