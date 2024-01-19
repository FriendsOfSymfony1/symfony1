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
 * sfWidgetFormInputRead represents an HTML text hidden input tag with readonly text input tag containing text to read.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormInputRead extends \sfWidgetFormInput
{
    /**
     * Render the current widget.
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
        $attributes = array_merge($this->attributes, $attributes);
        $this->attributes = [];

        $tag = parent::render($name, $value, [], $errors);

        $style = 'border: 0;';
        if (isset($attributes['style'])) {
            $style .= ' '.$attributes['style'];
            unset($attributes['style']);
        }

        return $tag.$this->renderTag('input', array_merge([
            'type' => 'text',
            'value' => $this->getOption('text', $value),
            'readonly' => 'readonly',
            'style' => $style,
        ], $attributes));
    }

    /**
     * Configures the current widget.
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addOption('text');
        $this->setOption('type', 'hidden');
    }
}
