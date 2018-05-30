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
 * @package    symfony
 * @subpackage log
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfConsoleLogger extends sfStreamLogger
{
  /**
   * @see sfStreamLogger
   *
   * @param  sfEventDispatcher $dispatcher  A sfEventDispatcher instance
   * @param  array             $options     An array of options.
   *
   * @return void
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    $options['stream'] = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');

    parent::initialize($dispatcher, $options);
  }
}
