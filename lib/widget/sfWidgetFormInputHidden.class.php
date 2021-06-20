<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormInputHidden represents a hidden HTML input tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfWidgetFormInputHidden extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('is_hidden', true);
    $this->setOption('type', 'hidden');
    $this->addOption('multiple', false);
  }
  
  /**
    * Renders the widget.
    *
    * @param  string $name        The element name
    * @param  string $value       The value displayed in this widget
    * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
    * @param  array  $errors      An array of errors for the field
    *
    * @return string An HTML tag string
    *
    * @see sfWidgetForm
    */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
     if ($this->getOption('multiple'))
     {
       $name .= '[]';
       $attributes['multiple'] = $this->getOption('multiple');
      }
      return parent::render($name, $value, $attributes, $errors);
  }		
}
