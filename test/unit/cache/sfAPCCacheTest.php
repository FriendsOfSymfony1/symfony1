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

$plan = 64;
$t = new \lime_test($plan);

if (extension_loaded('apc')) {
    $cacheClass = 'sfAPCCache';
} elseif (extension_loaded('apcu')) {
    if ('5.1.22' === phpversion('apcu')) {
        $t->skip('APCu 5.1.22 has a regression and shouldn\'t be used', $plan);

        return;
    }
    $cacheClass = 'sfAPCuCache';
} else {
    $t->skip('APC or APCu must be loaded to run these tests', $plan);

    return;
}

try {
    new $cacheClass();
} catch (\sfInitializationException $e) {
    $t->skip($e->getMessage(), $plan);

    return;
}

if (!ini_get('apc.enable_cli')) {
    $t->skip('APC must be enable on CLI to run these tests', $plan);

    return;
}

require_once __DIR__.'/sfCacheDriverTests.class.php';

// setup
\sfConfig::set('sf_logging_enabled', false);

// ->initialize()
$t->diag('->initialize()');
$cache = new $cacheClass();
$cache->initialize();

// make sure expired keys are dropped
// see https://github.com/krakjoe/apcu/issues/391
ini_set('apc.use_request_time', 0);

\sfCacheDriverTests::launch($t, $cache);
