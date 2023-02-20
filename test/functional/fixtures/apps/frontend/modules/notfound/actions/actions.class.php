<?php

/**
 * notfound actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class notfoundActions extends sfActions
{
    public function executeIndex()
    {
        $this->getResponse()->setStatusCode(404);

        return $this->renderText('404');
    }
}
