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

try {
    new \sfXCacheCache();
} catch (\sfInitializationException $e) {
    $t->skip($e->getMessage(), $plan);

    return;
}

require_once __DIR__.'/sfCacheDriverTests.class.php';

// setup
\sfConfig::set('sf_logging_enabled', false);

// ->initialize()
$t->diag('->initialize()');
$cache = new \sfXCacheCache();
$cache->initialize();

\sfCacheDriverTests::launch($t, $cache);
