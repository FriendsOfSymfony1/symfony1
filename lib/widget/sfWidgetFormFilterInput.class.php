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
 * sfWidgetFormFilterInput represents an HTML input tag used for filtering text.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormFilterInput extends \sfWidgetForm
{
    /**
     * Renders the widget.
     *
     * @param string $name       The element name
     * @param string $value      The value displayed in this widget
     * @param array  $attributes An array of HTML attributes to be merged with the default HTML attributes
     * @param array  $errors     An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see \sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        $values = array_merge(['text' => '', 'is_empty' => false], is_array($value) ? $value : []);

        return strtr($this->getOption('template'), [
            '%input%' => $this->renderTag('input', array_merge(['type' => 'text', 'id' => $this->generateId($name), 'name' => $name.'[text]', 'value' => $values['text']], $attributes)),
            '%empty_checkbox%' => $this->getOption('with_empty') ? $this->renderTag('input', ['type' => 'checkbox', 'name' => $name.'[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '']) : '',
            '%empty_label%' => $this->getOption('with_empty') ? $this->renderContentTag('label', $this->translate($this->getOption('empty_label')), ['for' => $this->generateId($name.'[is_empty]')]) : '',
        ]);
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * with_empty:  Whether to add the empty checkbox (true by default)
     *  * empty_label: The label to use when using an empty checkbox
     *  * template:    The template to use to render the widget
     *                 Available placeholders: %input%, %empty_checkbox%, %empty_label%
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->addOption('with_empty', true);
        $this->addOption('empty_label', 'is empty');
        $this->addOption('template', '%input%<br />%empty_checkbox% %empty_label%');
    }
}
