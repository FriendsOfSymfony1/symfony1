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
 * cookie actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class cookieActions extends \sfActions
{
    public function executeIndex($request)
    {
        return $this->renderText('<p>'.$request->getCookie('foo').'.'.$request->getCookie('bar').'-'.$request->getCookie('foobar').'</p>');
    }

    public function executeSetCookie($request)
    {
        $this->getResponse()->setCookie('foobar', 'barfoo');

        return \sfView::NONE;
    }

    public function executeRemoveCookie($request)
    {
        $this->getResponse()->setCookie('foobar', 'foofoobar', time() - 10);

        return \sfView::NONE;
    }
}
