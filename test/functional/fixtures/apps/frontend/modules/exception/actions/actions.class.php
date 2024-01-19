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
 * exception actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class exceptionActions extends \sfActions
{
    public function executeNoException()
    {
        return $this->renderText('foo');
    }

    public function executeThrowsException()
    {
        throw new \Exception('Exception message');
    }

    public function executeThrowsSfException()
    {
        throw new \sfException('sfException message');
    }
}
