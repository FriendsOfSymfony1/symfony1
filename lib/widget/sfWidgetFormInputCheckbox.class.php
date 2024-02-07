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
 * sfWidgetFormInputCheckbox represents an HTML checkbox tag.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormInputCheckbox extends \sfWidgetFormInput
{
    /**
     * Constructor.
     *
     * Available options:
     *
     *  - value_attribute_value: The "value" attribute value to set for the checkbox
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetFormInput
     */
    public function __construct($options = [], $attributes = [])
    {
        $this->addOption('value_attribute_value');

        parent::__construct($options, $attributes);
    }

    /**
     * Renders the widget.
     *
     * @param string $name       The element name
     * @param string $value      The this widget is checked if value is not null
     * @param array  $attributes An array of HTML attributes to be merged with the default HTML attributes
     * @param array  $errors     An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see \sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        if (null !== $value && false !== $value) {
            $attributes['checked'] = 'checked';
        }

        if (!isset($attributes['value']) && null !== $this->getOption('value_attribute_value')) {
            $attributes['value'] = $this->getOption('value_attribute_value');
        }

        return parent::render($name, null, $attributes, $errors);
    }

    /**
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetFormInput
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->setOption('type', 'checkbox');

        if (isset($attributes['value'])) {
            $this->setOption('value_attribute_value', $attributes['value']);
        }
    }
}
