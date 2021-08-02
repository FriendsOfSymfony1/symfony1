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
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfWidgetFormSelectCheckbox extends sfWidgetFormChoiceBase
{
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
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $template = '
    <ul>
        <li><label class="checkbox_list_group_label">%group%</label></li>
        <li>
           %options%
        </li>
    </ul>
    ';

    $this->addOption('class', 'checkbox_list');
    $this->addOption('label_separator', '&nbsp;');
    $this->addOption('separator', "\n");
    $this->addOption('formatter', array($this, 'formatter'));
    $this->addOption('template', $template);
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ('[]' != substr($name, -2))
    {
      $name .= '[]';
    }

    if (null === $value)
    {
      $value = array();
    }

    $choices = $this->getChoices();
    return $this->groupChoices($name, $value, $choices, $attributes, 1);
  }

  protected function groupChoices($name, $value, $choices, $attributes, $level = 0){
    // with groups?
    if (count($choices) && is_array(current($choices)))
    {
      $parts = array();
      foreach ($choices as $key => $option)
      {
        $group = sprintf("%s %s", str_repeat("&mdash;", $level), $key);
        $parts[] = strtr($this->getOption('template'), array('%group%' => $group, '%options%' => $this->groupChoices($name, $value, $option, $attributes, $level + 1)));
      }

      return implode("\n", $parts);
    }
    else
    {
      return $this->formatChoices($name, $value, $choices, $attributes, $level);
    }
  }

  protected function formatChoices($name, $value, $choices, $attributes, $level)
  {
    $inputs = array();
    foreach ($choices as $key => $option)
    {
      $baseAttributes = array(
        'name'  => $name,
        'type'  => 'checkbox',
        'value' => self::escapeOnce($key),
        'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
      );

      if ((is_array($value) && in_array((string) $key, $value)) || (is_string($value) && (string) $key == (string) $value))
      {
        $baseAttributes['checked'] = 'checked';
      }

      $pre = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);

      $inputs[$id] = array(
        'input' => $pre.$this->renderTag('input', array_merge($baseAttributes, $attributes)),
        'label' => $this->renderContentTag('label', self::escapeOnce($option), array('for' => $id)),
      );
    }

    return call_user_func($this->getOption('formatter'), $this, $inputs);
  }

  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $rows[] = $this->renderContentTag('li', $input['input'].$this->getOption('label_separator').$input['label']);
    }

    return !$rows ? '' : $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }
}
