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
 * format actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class formatActions extends \sfActions
{
    public function executeIndex($request)
    {
        if ('xml' == $request->getRequestFormat()) {
            $this->setLayout('layout');
        }
    }

    public function executeForTheIPhone($request)
    {
        $this->setTemplate('index');
    }

    public function executeJs($request)
    {
        $request->setRequestFormat('js');
    }

    public function executeJsWithAccept()
    {
        $this->setTemplate('index');
    }

    public function executeThrowsException()
    {
        throw new \Exception('Descriptive message');
    }

    public function executeThrowsNonDebugException()
    {
        \sfConfig::set('sf_debug', false);

        throw new \Exception('Descriptive message');
    }
}
