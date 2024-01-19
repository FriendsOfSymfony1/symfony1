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
 * sfWidgetFormTime represents a time widget.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormTime extends \sfWidgetForm
{
    /**
     * Renders the widget.
     *
     * @param string $name       The element name
     * @param string $value      The time displayed in this widget
     * @param array  $attributes An array of HTML attributes to be merged with the default HTML attributes
     * @param array  $errors     An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see \sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        // convert value to an array
        $default = ['hour' => null, 'minute' => null, 'second' => null];
        if (is_array($value)) {
            $value = array_merge($default, $value);
        } else {
            $value = ctype_digit((string) $value) ? (int) $value : strtotime((string) $value);
            if (false === $value) {
                $value = $default;
            } else {
                // int cast required to get rid of leading zeros
                $value = ['hour' => (int) date('H', $value), 'minute' => (int) date('i', $value), 'second' => (int) date('s', $value)];
            }
        }

        $time = [];
        $emptyValues = $this->getOption('empty_values');

        // hours
        $widget = new \sfWidgetFormSelect(['choices' => $this->getOption('can_be_empty') ? ['' => $emptyValues['hour']] + $this->getOption('hours') : $this->getOption('hours'), 'id_format' => $this->getOption('id_format')], array_merge($this->attributes, $attributes));
        $time['%hour%'] = $widget->render($name.'[hour]', $value['hour']);

        // minutes
        $widget = new \sfWidgetFormSelect(['choices' => $this->getOption('can_be_empty') ? ['' => $emptyValues['minute']] + $this->getOption('minutes') : $this->getOption('minutes'), 'id_format' => $this->getOption('id_format')], array_merge($this->attributes, $attributes));
        $time['%minute%'] = $widget->render($name.'[minute]', $value['minute']);

        if ($this->getOption('with_seconds')) {
            // seconds
            $widget = new \sfWidgetFormSelect(['choices' => $this->getOption('can_be_empty') ? ['' => $emptyValues['second']] + $this->getOption('seconds') : $this->getOption('seconds'), 'id_format' => $this->getOption('id_format')], array_merge($this->attributes, $attributes));
            $time['%second%'] = $widget->render($name.'[second]', $value['second']);
        }

        return strtr($this->getOption('with_seconds') ? $this->getOption('format') : $this->getOption('format_without_seconds'), $time);
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * format:                 The time format string (%hour%:%minute%:%second%)
     *  * format_without_seconds: The time format string without seconds (%hour%:%minute%)
     *  * with_seconds:           Whether to include a select for seconds (false by default)
     *  * hours:                  An array of hours for the hour select tag (optional)
     *  * minutes:                An array of minutes for the minute select tag (optional)
     *  * seconds:                An array of seconds for the second select tag (optional)
     *  * can_be_empty:           Whether the widget accept an empty value (true by default)
     *  * empty_values:           An array of values to use for the empty value (empty string for hours, minutes, and seconds by default)
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->addOption('format', '%hour%:%minute%:%second%');
        $this->addOption('format_without_seconds', '%hour%:%minute%');
        $this->addOption('with_seconds', false);
        $this->addOption('hours', parent::generateTwoCharsRange(0, 23));
        $this->addOption('minutes', parent::generateTwoCharsRange(0, 59));
        $this->addOption('seconds', parent::generateTwoCharsRange(0, 59));

        $this->addOption('can_be_empty', true);
        $this->addOption('empty_values', ['hour' => '', 'minute' => '', 'second' => '']);
    }
}
