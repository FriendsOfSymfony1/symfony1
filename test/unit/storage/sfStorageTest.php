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

$t = new \lime_test(0);

class myStorage extends \sfStorage
{
    public function read($key)
    {
    }

    public function remove($key)
    {
    }

    public function shutdown()
    {
    }

    public function write($key, $data)
    {
    }

    public function regenerate($destroy = false)
    {
    }
}

class fakeStorage
{
}
