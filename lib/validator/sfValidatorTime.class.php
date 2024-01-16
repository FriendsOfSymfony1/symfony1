<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorTime validates a time. It also converts the input value to a valid time.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Fabian Lange <fabian.lange@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorTime extends sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * time_format:       A regular expression that dates must match
     *  * time_output:       The format to use when returning a date with time (default to H:i:s)
     *  * time_format_error: The date format to use when displaying an error for a bad_format error
     *
     * Available error codes:
     *
     *  * bad_format
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addMessage('bad_format', '"%value%" does not match the time format (%time_format%).');

        $this->addOption('time_format', null);
        $this->addOption('time_output', 'H:i:s');
        $this->addOption('time_format_error');
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        if (is_array($value)) {
            $clean = $this->convertTimeArrayToTimestamp($value);
        } elseif ($regex = $this->getOption('time_format')) {
            if (!preg_match($regex, $value, $match)) {
                throw new sfValidatorError($this, 'bad_format', ['value' => $value, 'time_format' => $this->getOption('time_format_error') ?: $this->getOption('time_format')]);
            }

            $clean = $this->convertTimeArrayToTimestamp($match);
        } elseif (!ctype_digit($value)) {
            $clean = strtotime($value);
            if (false === $clean) {
                throw new sfValidatorError($this, 'invalid', ['value' => $value]);
            }
        } else {
            $clean = (int) $value;
        }

        return $clean === $this->getEmptyValue() ? $clean : date($this->getOption('time_output'), $clean);
    }

    /**
     * Converts an array representing a time to a timestamp.
     *
     * The array can contains the following keys: hour, minute, second
     *
     * @param array $value An array of date elements
     *
     * @return int A timestamp
     */
    protected function convertTimeArrayToTimestamp($value)
    {
        // all elements must be empty or a number
        foreach (['hour', 'minute', 'second'] as $key) {
            if (isset($value[$key]) && !ctype_digit((string) $value[$key]) && !empty($value[$key])) {
                throw new sfValidatorError($this, 'invalid', ['value' => $value]);
            }
        }

        // if second is set, minute and hour must be set
        // if minute is set, hour must be set
        if (
            $this->isValueSet($value, 'second') && (!$this->isValueSet($value, 'minute') || !$this->isValueSet($value, 'hour'))
            || $this->isValueSet($value, 'minute') && !$this->isValueSet($value, 'hour')
        ) {
            throw new sfValidatorError($this, 'invalid', ['value' => $value]);
        }

        $clean = mktime(
            isset($value['hour']) ? (int) $value['hour'] : 0,
            isset($value['minute']) ? (int) $value['minute'] : 0,
            isset($value['second']) ? (int) $value['second'] : 0
        );

        if (false === $clean) {
            throw new sfValidatorError($this, 'invalid', ['value' => var_export($value, true)]);
        }

        return $clean;
    }

    protected function isValueSet($values, $key)
    {
        return isset($values[$key]) && !in_array($values[$key], [null, ''], true);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function isEmpty($value)
    {
        if (is_array($value)) {
            // array is not empty when a value is found
            foreach ($value as $key => $val) {
                // int and string '0' are 'empty' values that are explicitly accepted
                if (0 === $val || '0' === $val || !empty($val)) {
                    return false;
                }
            }

            return true;
        }

        return parent::isEmpty($value);
    }
}
