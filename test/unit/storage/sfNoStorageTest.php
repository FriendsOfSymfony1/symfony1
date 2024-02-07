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

// initialize the storage
$storage = new \sfNoStorage();

$t->ok($storage instanceof \sfStorage, 'sfNoStorage is an instance of sfStorage');

// shutdown the storage
$storage->shutdown();
