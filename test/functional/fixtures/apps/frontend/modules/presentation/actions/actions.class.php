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
 * presentation actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class presentationActions extends \sfActions
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
