<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorEqual validates a value compared to another value.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Yvann Boucher <yboucher@groupe-exp.com>
 * @version    SVN: $Id$
 */
class sfValidatorEqual extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * strict: If it's equal strictly or not
   *  * value: The value to compare
   *
   * Available error codes:
   *
   *  * not_equal
   *  * not_strictly_equal
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('value');
    $this->addOption('strict', false);

    $this->addMessage('not_equal', '%value% is not equal to %compared_value%');
    $this->addMessage('not_strictly_equal', '%value% is not strictly equal to %compared_value%');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $isStrict = $this->getOption('strict');

    if (($isStrict && $value !== $this->getOption('value')) || (!$isStrict && $value != $this->getOption('value')))
    {
      throw new sfValidatorError($this, $isStrict ? 'not_strictly_equal' : 'not_equal', array(
        'value'          => $value,
        'compared_value' => $this->getOption('value'),
      ));
    }

    return $value;
  }
}
