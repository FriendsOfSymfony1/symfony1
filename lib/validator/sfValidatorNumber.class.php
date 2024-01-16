<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorNumber validates a number (integer or float). It also converts the input value to a float.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorNumber extends sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * max: The maximum value allowed
     *  * min: The minimum value allowed
     *
     * Available error codes:
     *
     *  * max
     *  * min
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addMessage('max', '"%value%" must be at most %max%.');
        $this->addMessage('min', '"%value%" must be at least %min%.');

        $this->addOption('min');
        $this->addOption('max');

        $this->setMessage('invalid', '"%value%" is not a number.');
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        if (!is_numeric($value)) {
            throw new sfValidatorError($this, 'invalid', ['value' => $value]);
        }

        $clean = (float) $value;

        if ($this->hasOption('max') && $clean > $this->getOption('max')) {
            throw new sfValidatorError($this, 'max', ['value' => $value, 'max' => $this->getOption('max')]);
        }

        if ($this->hasOption('min') && $clean < $this->getOption('min')) {
            throw new sfValidatorError($this, 'min', ['value' => $value, 'min' => $this->getOption('min')]);
        }

        return $clean;
    }
}
