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

class myCache extends \sfCache
{
    public function get($key, $default = null)
    {
    }

    public function has($key)
    {
    }

    public function set($key, $data, $lifetime = null)
    {
    }

    public function remove($key)
    {
    }

    public function clean($mode = \sfCache::ALL)
    {
    }

    public function getTimeout($key)
    {
    }

    public function getLastModified($key)
    {
    }

    public function removePattern($pattern, $delimiter = ':')
    {
    }
}

class fakeCache
{
}

$t = new \lime_test(1);

// ->initialize()
$t->diag('->initialize()');
$cache = new \myCache();
$cache->initialize(['foo' => 'bar']);
$t->is($cache->getOption('foo'), 'bar', '->initialize() takes an array of options as its first argument');
