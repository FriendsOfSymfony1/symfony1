<?php

/**
 * autoload actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class autoloadActions extends sfActions
{
    public function executeIndex()
    {
        $this->lib1 = myLibClass::ping();
        $this->lib2 = myAppsFrontendLibClass::ping();
        $this->lib3 = myAppsFrontendModulesAutoloadLibClass::ping();
        $this->lib4 = class_exists('myPluginsSfAutoloadPluginModulesAutoloadPluginLibClass') ? 'pong' : 'nopong';
    }

    public function executeMyAutoload()
    {
        $this->o = new myAutoloadedClass();
    }
}
