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
 * escaping actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class escapingActions extends \sfActions
{
    public function preExecute()
    {
        $this->var = 'Lorem <strong>ipsum</strong> dolor sit amet.';
        $this->setLayout(false);
        $this->setTemplate('index');
    }

    public function executeOn()
    {
        \sfConfig::set('sf_escaping_strategy', true);
    }

    public function executeOff()
    {
        \sfConfig::set('sf_escaping_strategy', false);
    }
}
