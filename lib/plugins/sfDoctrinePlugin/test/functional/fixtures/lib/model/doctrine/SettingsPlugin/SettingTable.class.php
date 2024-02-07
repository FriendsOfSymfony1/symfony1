<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class SettingTable extends \PluginSettingTable
{
    public static function getInstance()
    {
        return \Doctrine_Core::getTable('Setting');
    }
}
