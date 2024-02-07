<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class autoloadPluginActions extends \sfActions
{
    public function executeIndex()
    {
        $this->lib1 = \myLibClass::ping();
        $this->lib2 = \myAppsFrontendLibClass::ping();
        $this->lib3 = \myPluginsSfAutoloadPluginModulesAutoloadPluginLibClass::ping();
    }
}
