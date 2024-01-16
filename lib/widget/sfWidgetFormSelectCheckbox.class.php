<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSelectCheckbox represents an array of checkboxes.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormSelectCheckbox extends sfWidgetFormChoiceBase
{
    /**
     * Renders the widget.
     *
     * @param string $name       The element name
     * @param string $value      The value selected in this widget
     * @param array  $attributes An array of HTML attributes to be merged with the default HTML attributes
     * @param array  $errors     An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        if ('[]' != substr($name, -2)) {
            $name .= '[]';
        }

        if (null === $value) {
            $value = [];
        }

        $choices = $this->getChoices();

        // with groups?
        if (count($choices) && is_array(current($choices))) {
            $parts = [];
            foreach ($choices as $key => $option) {
                $parts[] = strtr($this->getOption('template'), ['%group%' => $key, '%options%' => $this->formatChoices($name, $value, $option, $attributes)]);
            }

            return implode("\n", $parts);
        }

        return $this->formatChoices($name, $value, $choices, $attributes);
    }

    public function formatter($widget, $inputs)
    {
        $rows = [];
        foreach ($inputs as $input) {
            $rows[] = $this->renderContentTag('li', $input['input'].$this->getOption('label_separator').$input['label']);
        }

        return !$rows ? '' : $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), ['class' => $this->getOption('class')]);
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * choices:         An array of possible choices (required)
     *  * label_separator: The separator to use between the input checkbox and the label
     *  * class:           The class to use for the main <ul> tag
     *  * separator:       The separator to use between each input checkbox
     *  * formatter:       A callable to call to format the checkbox choices
     *                     The formatter callable receives the widget and the array of inputs as arguments
     *  * template:        The template to use when grouping option in groups (%group% %options%)
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see sfWidgetFormChoiceBase
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addOption('class', 'checkbox_list');
        $this->addOption('label_separator', '&nbsp;');
        $this->addOption('separator', "\n");
        $this->addOption('formatter', [$this, 'formatter']);
        $this->addOption('template', '%group% %options%');
    }

    protected function formatChoices($name, $value, $choices, $attributes)
    {
        $inputs = [];
        foreach ($choices as $key => $option) {
            $baseAttributes = [
                'name' => $name,
                'type' => 'checkbox',
                'value' => self::escapeOnce($key),
                'id' => $id = $this->generateId($name, self::escapeOnce($key)),
            ];

            if ((is_array($value) && in_array((string) $key, $value)) || (is_string($value) && (string) $key == (string) $value)) {
                $baseAttributes['checked'] = 'checked';
            }

            $inputs[$id] = [
                'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
                'label' => $this->renderContentTag('label', self::escapeOnce($option), ['for' => $id]),
            ];
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }
}
