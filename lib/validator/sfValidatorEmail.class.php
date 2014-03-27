<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorEmail validates emails.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfValidatorEmail extends sfValidatorBase
{
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = filter_var($value, FILTER_VALIDATE_EMAIL);
    if (!$clean)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    return $clean;
  }
}
