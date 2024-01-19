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

class ProjectDumper extends \sfServiceContainerDumper
{
}

$builder = new \sfServiceContainerBuilder();
$dumper = new \ProjectDumper($builder);

try {
    $dumper->dump();
    $t->fail('->dump() returns a LogicException if the dump() method has not been overriden by a children class');
} catch (\LogicException $e) {
    $t->pass('->dump() returns a LogicException if the dump() method has not been overriden by a children class');
}
