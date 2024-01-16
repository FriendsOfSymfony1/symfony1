<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfConsoleLogger logs messages to the console.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfConsoleLogger extends sfStreamLogger
{
    /**
     * @see sfStreamLogger
     *
     * @param sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array             $options    an array of options
     */
    public function initialize(sfEventDispatcher $dispatcher, $options = [])
    {
        $options['stream'] = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');

        parent::initialize($dispatcher, $options);
    }
}
