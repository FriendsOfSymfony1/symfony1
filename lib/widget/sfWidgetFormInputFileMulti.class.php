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
 * sfWidgetFormInputFileMulti represents an upload HTML input tag with multiple option.
 *
 * @author     Vincent Chabot <vchabot@groupe-exp.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormInputFileMulti extends \sfWidgetFormInputFile
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
        if ($this->getOption('multiple')) {
            $name .= '[]';
            $attributes['multiple'] = $this->getOption('multiple');
        }

        return parent::render($name, $value, $attributes, $errors);
    }

    /**
     * Configures the current widget.
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetFormInput
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addOption('multiple', true);
    }
}
