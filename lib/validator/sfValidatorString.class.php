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
 * sfValidatorString validates a string. It also converts the input value to a string.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorString extends \sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * max_length: The maximum length of the string
     *  * min_length: The minimum length of the string
     *
     * Available error codes:
     *
     *  * max_length
     *  * min_length
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see \sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addMessage('max_length', '"%value%" is too long (%max_length% characters max).');
        $this->addMessage('min_length', '"%value%" is too short (%min_length% characters min).');

        $this->addOption('max_length');
        $this->addOption('min_length');

        $this->setOption('empty_value', '');
    }

    /**
     * @see \sfValidatorBase
     */
    protected function doClean($value)
    {
        $clean = (string) $value;

        $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);

        if ($this->hasOption('max_length') && $length > $this->getOption('max_length')) {
            throw new \sfValidatorError($this, 'max_length', ['value' => $value, 'max_length' => $this->getOption('max_length')]);
        }

        if ($this->hasOption('min_length') && $length < $this->getOption('min_length')) {
            throw new \sfValidatorError($this, 'min_length', ['value' => $value, 'min_length' => $this->getOption('min_length')]);
        }

        return $clean;
    }
}
