<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorBoolean validates a boolean. It also converts the input value to a valid boolean.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorBoolean extends sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * true_values:  The list of true values
     *  * false_values: The list of false values
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addOption('true_values', ['true', 't', 'yes', 'y', 'on', '1']);
        $this->addOption('false_values', ['false', 'f', 'no', 'n', 'off', '0']);

        $this->setOption('required', false);
        $this->setOption('empty_value', false);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        $checkValue = 0 === $value ? '0' : $value;
        if (in_array($checkValue, $this->getOption('true_values'))) {
            return true;
        }

        if (in_array($checkValue, $this->getOption('false_values'))) {
            return false;
        }

        throw new sfValidatorError($this, 'invalid', ['value' => $value]);
    }
}
