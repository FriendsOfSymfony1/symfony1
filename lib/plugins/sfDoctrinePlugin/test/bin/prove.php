<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('SYMFONY_LIB_DIR', realpath(dirname(__FILE__).'/../../../..'));

require SYMFONY_LIB_DIR.'/vendor/lime/lime.php';

require SYMFONY_LIB_DIR.'/util/sfFinder.class.php';

$h = new \lime_harness();
$h->base_dir = realpath(dirname(__FILE__).'/..');

$h->register(\sfFinder::type('file')->prune('fixtures')->name('*Test.php')->in([
    // unit tests
    $h->base_dir.'/unit',
    // functional tests
    $h->base_dir.'/functional',
]));

exit($h->run() ? 0 : 1);
