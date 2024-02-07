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

// ::removeObjects()
$t->diag('::removeObjects()');
$objectArray = ['foo', 42, new \sfDebug(), ['bar', 23, new \lime_test(null)]];
$cleanedArray = ['foo', 42, 'sfDebug Object()', ['bar', 23, 'lime_test Object()']];
$t->is_deeply(\sfDebug::removeObjects($objectArray), $cleanedArray, '::removeObjects() converts objects to String representations using the class name');

$t->diag('::shortenFilePath()');
$t->is(\sfDebug::shortenFilePath(null), null, '->shortenFilePath() handles a null value');
