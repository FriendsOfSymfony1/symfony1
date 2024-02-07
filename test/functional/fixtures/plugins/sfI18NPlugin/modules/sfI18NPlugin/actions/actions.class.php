<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfI18NPlugin actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfI18NPluginActions extends \sfActions
{
    public function executeIndex()
    {
        $i18n = $this->getContext()->getI18N();

        $this->test = $i18n->__('an english sentence from plugin');
        $this->localTest = $i18n->__('a local english sentence from plugin');
        $this->otherTest = $i18n->__('another english sentence from plugin');
        $this->yetAnotherTest = $i18n->__('yet another english sentence from plugin');

        $this->testForPluginI18N = $i18n->__('an english sentence from plugin - global');
    }
}
