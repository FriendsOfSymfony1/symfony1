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

$autoload = \sfCoreAutoload::getInstance();
$t->is($autoload->getClassPath('sfaction'), $autoload->getBaseDir().'/action/sfAction.class.php', '"sfCoreAutoload" is case-insensitive');
