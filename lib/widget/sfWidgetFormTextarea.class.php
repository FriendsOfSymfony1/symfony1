<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextarea represents a textarea HTML tag.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormTextarea extends sfWidgetForm
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
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        return $this->renderContentTag('textarea', self::escapeOnce($value), array_merge(['name' => $name], $attributes));
    }

    /**
     * Configures the current widget.
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->setAttribute('rows', 4);
        $this->setAttribute('cols', 30);
    }
}
