<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include __DIR__.'/../bootstrap/unit.php';

$t = new \lime_test(1);

$t->pass('this is ok');
