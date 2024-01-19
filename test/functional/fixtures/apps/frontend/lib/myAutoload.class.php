<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class myAutoload
{
    public static function autoload($class)
    {
        if ('myAutoloadedClass' == $class) {
            require_once __DIR__.'/myAutoloadedClass.class.php';

            return true;
        }

        return false;
    }
}
