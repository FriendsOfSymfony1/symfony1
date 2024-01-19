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
 * sfValidatorDateRange validates a range of date. It also converts the input values to valid dates.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorDateRange extends \sfValidatorBase
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * from_date:   The from date validator (required)
     *  * to_date:     The to date validator (required)
     *  * from_field:  The name of the "from" date field (optional, default: from)
     *  * to_field:    The name of the "to" date field (optional, default: to)
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see \sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->setMessage('invalid', 'The begin date must be before the end date.');

        $this->addRequiredOption('from_date');
        $this->addRequiredOption('to_date');
        $this->addOption('from_field', 'from');
        $this->addOption('to_field', 'to');
    }

    /**
     * @see \sfValidatorBase
     */
    protected function doClean($value)
    {
        $fromField = $this->getOption('from_field');
        $toField = $this->getOption('to_field');

        $value[$fromField] = $this->getOption('from_date')->clean(isset($value[$fromField]) ? $value[$fromField] : null);
        $value[$toField] = $this->getOption('to_date')->clean(isset($value[$toField]) ? $value[$toField] : null);

        if ($value[$fromField] && $value[$toField]) {
            $v = new \sfValidatorSchemaCompare($fromField, \sfValidatorSchemaCompare::LESS_THAN_EQUAL, $toField, ['throw_global_error' => true], ['invalid' => $this->getMessage('invalid')]);
            $v->clean($value);
        }

        return $value;
    }
}
