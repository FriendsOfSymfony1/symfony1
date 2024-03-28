<?php

/**
 * presentation actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class presentationActions extends sfActions
{
    public function executeIndex()
    {
        $this->foo = $this->getController()->getPresentationFor('presentation', 'foo');
    }

    public function executeFoo()
    {
        $this->setLayout(false);
    }
}
