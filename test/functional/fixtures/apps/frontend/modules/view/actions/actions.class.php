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
 * view actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class viewActions extends \sfActions
{
    public function executeIndex()
    {
        $this->setTemplate('foo');
    }

    public function executePlain()
    {
    }

    public function executeImage()
    {
    }
}
