<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(5);

// call()
$t->diag('call()');
$c = new sfCallable('trim');
$t->is($c->call('  foo  '), 'foo', '->call() calls the callable with the given arguments');

class TrimTest
{
    public static function trimStatic($text)
    {
        return trim($text);
    }

    public function trim($text)
    {
        return trim($text);
    }
}

$c = new sfCallable(['TrimTest', 'trimStatic']);
$t->is($c->call('  foo  '), 'foo', '->call() calls the callable with the given arguments');

$c = new sfCallable([new TrimTest(), 'trim']);
$t->is($c->call('  foo  '), 'foo', '->call() calls the callable with the given arguments');

$c = new sfCallable('nonexistantcallable');

try {
    $c->call();
    $t->fail('->call() throws an sfException if the callable is not valid');
} catch (sfException $e) {
    $t->pass('->call() throws an sfException if the callable is not valid');
}

// ->getCallable()
$t->diag('->getCallable()');
$c = new sfCallable('trim');
$t->is($c->getCallable(), 'trim', '->getCallable() returns the current callable');
