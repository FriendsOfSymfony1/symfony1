<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorCSRFToken checks that the token is valid.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfValidatorCSRFToken extends sfValidatorBase
{
  /**
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('token');

    $this->setOption('required', true);

    $this->addMessage('csrf_attack', 'CSRF attack detected.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if ($value != $this->getOption('token'))
    {
      throw new sfValidatorError($this, 'csrf_attack');
    }

    return $value;
  }
}
