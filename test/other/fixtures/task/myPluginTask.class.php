<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class myPluginTask extends \sfBaseTask
{
    public function configure()
    {
        $this->namespace = 'p';
        $this->name = 'run';
    }

    public function execute($arguments = [], $options = [])
    {
    }
}
