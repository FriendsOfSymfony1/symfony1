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

$t = new \lime_test(8);

// ->initialize()
$t->diag('->initialize()');
$cache = new \sfNoCache();

// ->get() ->set() ->has() ->remove() ->removePattern() ->clean() ->getLastModified() ->getTimeout()
$t->diag('->get() ->set() ->has() ->remove() ->removePattern() ->clean() ->getLastModified() ->getTimeout()');
$t->is($cache->get('foo'), null, '->get() always returns "null"');
$t->is($cache->set('foo', 'bar'), true, '->set() always returns "true"');
$t->is($cache->has('foo'), false, '->has() always returns "false"');
$t->is($cache->remove('foo'), true, '->remove() always returns "true"');
$t->is($cache->removePattern('**'), true, '->removePattern() always returns "true"');
$t->is($cache->clean(), true, '->clean() always returns "true"');
$t->is($cache->getLastModified('foo'), 0, '->getLastModified() always returns "0"');
$t->is($cache->getTimeout('foo'), 0, '->getTimeout() always returns "0"');
