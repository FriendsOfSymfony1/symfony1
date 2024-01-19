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

$autoload = \sfSimpleAutoload::getInstance();
$autoload->addFile(__DIR__.'/../sfEventDispatcherTest.class.php');
$autoload->register();

$t->is(class_exists('myeventdispatchertest'), true, '"sfSimpleAutoload" is case insensitive');
