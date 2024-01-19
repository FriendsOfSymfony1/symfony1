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

require_once __DIR__.'/sfCacheDriverTests.class.php';

$plan = 129;
$t = new \lime_test($plan);

if (!extension_loaded('SQLite') && !extension_loaded('pdo_SQLite')) {
    $t->skip('SQLite extension not loaded, skipping tests', $plan);

    return;
}

try {
    new \sfSQLiteCache(['database' => ':memory:']);
} catch (\sfInitializationException $e) {
    $t->skip($e->getMessage(), $plan);

    return;
}

// ->initialize()
$t->diag('->initialize()');

try {
    $cache = new \sfSQLiteCache();
    $t->fail('->initialize() throws an sfInitializationException exception if you don\'t pass a "database" parameter');
} catch (\sfInitializationException $e) {
    $t->pass('->initialize() throws an sfInitializationException exception if you don\'t pass a "database" parameter');
}

// database in memory
$cache = new \sfSQLiteCache(['database' => ':memory:']);

\sfCacheDriverTests::launch($t, $cache);

// database on disk
$database = tempnam('/tmp/cachedir', 'tmp');
unlink($database);
$cache = new \sfSQLiteCache(['database' => $database]);
\sfCacheDriverTests::launch($t, $cache);
unlink($database);
