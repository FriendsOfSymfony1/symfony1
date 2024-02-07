<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$name = '*';
$verbose = false;

if (isset($argv[1])) {
    $name = $argv[1];
    $verbose = true;
}

require_once __DIR__.'/../../lib/vendor/lime/lime.php';

require_once __DIR__.'/../../lib/util/sfFinder.class.php';

$h = new \lime_harness();
$h->base_dir = realpath(__DIR__.'/..');

// unit tests
$h->register_glob(sprintf('%s/unit/*/%sTest.php', $h->base_dir, $name));
$h->register_glob(sprintf('%s/unit/*/*/%sTest.php', $h->base_dir, $name));
$h->register_glob(sprintf('%s/../lib/plugins/*/unit/%sTest.php', $h->base_dir, $name));
$h->register_glob(sprintf('%s/../lib/plugins/*/unit/*/%sTest.php', $h->base_dir, $name));

// functional tests
$h->register_glob(sprintf('%s/functional/%sTest.php', $h->base_dir, $name));
$h->register_glob(sprintf('%s/functional/*/%sTest.php', $h->base_dir, $name));
$h->register_glob(sprintf('%s/../lib/plugins/*/functional/%sTest.php', $h->base_dir, $name));

$c = new \lime_coverage($h);
$c->extension = '.class.php';
$c->verbose = $verbose;
$c->base_dir = realpath(__DIR__.'/../../lib');

$finder = \sfFinder::type('file')->name($name.'.class.php')->prune('vendor')->prune('test')->prune('data');

$c->register($finder->in($c->base_dir));
$c->run();
