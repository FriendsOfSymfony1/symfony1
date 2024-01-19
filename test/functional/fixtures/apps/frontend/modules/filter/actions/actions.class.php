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
 * filter actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class filterActions extends \sfActions
{
    public function executeIndex()
    {
        return $this->renderText('foo');
    }

    public function executeIndexWithForward()
    {
        $this->forward('filter', 'index');
    }
}
