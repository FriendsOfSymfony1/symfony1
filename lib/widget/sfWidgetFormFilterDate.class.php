<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormFilterDate represents a date filter widget.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormFilterDate extends sfWidgetFormDateRange
{
    /**
     * Renders the widget.
     *
     * @param string $name       The element name
     * @param string $value      The date displayed in this widget
     * @param array  $attributes An array of HTML attributes to be merged with the default HTML attributes
     * @param array  $errors     An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        $values = array_merge(['is_empty' => ''], is_array($value) ? $value : []);

        return strtr($this->getOption('filter_template'), [
            '%date_range%' => parent::render($name, $value, $attributes, $errors),
            '%empty_checkbox%' => $this->getOption('with_empty') ? $this->renderTag('input', ['type' => 'checkbox', 'name' => $name.'[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '']) : '',
            '%empty_label%' => $this->getOption('with_empty') ? $this->renderContentTag('label', $this->translate($this->getOption('empty_label')), ['for' => $this->generateId($name.'[is_empty]')]) : '',
        ]);
    }

    /**
     * Configures the current widget.
     *
     * Available options:
     *
     *  * with_empty:      Whether to add the empty checkbox (true by default)
     *  * empty_label:     The label to use when using an empty checkbox
     *  * template:        The template used for from date and to date
     *                     Available placeholders: %from_date%, %to_date%
     *  * filter_template: The template to use to render the widget
     *                     Available placeholders: %date_range%, %empty_checkbox%, %empty_label%
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addOption('with_empty', true);
        $this->addOption('empty_label', 'is empty');
        $this->addOption('template', 'from %from_date%<br />to %to_date%');
        $this->addOption('filter_template', '%date_range%<br />%empty_checkbox% %empty_label%');
    }
}
