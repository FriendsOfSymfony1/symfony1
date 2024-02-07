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

// __construct() ->__toString()
$t->diag('__construct() ->__toString()');

$ref = new \sfServiceParameter('foo');
$t->is((string) $ref, 'foo', '__construct() sets the id of the parameter, which is used for the __toString() method');
