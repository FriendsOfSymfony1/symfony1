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
 * sfWidgetFormInput represents an HTML input tag.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormInput extends \sfWidgetForm
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
        return $this->renderTag('input', array_merge(['type' => $this->getOption('type'), 'name' => $name, 'value' => $value], $attributes));
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * type: The widget type
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->addRequiredOption('type');

        // to maintain BC with symfony 1.2
        $this->setOption('type', 'text');
    }
}
