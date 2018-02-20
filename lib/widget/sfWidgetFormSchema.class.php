<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSchema represents an array of fields.
 *
 * A field is a named validator.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfWidgetFormSchema extends sfWidgetForm implements ArrayAccess
{
  const
    FIRST  = 'first',
    LAST   = 'last',
    BEFORE = 'before',
    AFTER  = 'after';

  protected static
    $defaultFormatterName = 'table';

  protected
    $formFormatters = array(),
    $fields         = array(),
    $positions      = array(),
    $helps          = array();

  /**
   * Constructor.
   *
   * The first argument can be:
   *
   *  * null
   *  * an array of sfWidget instances
   *
   * Available options:
   *
   *  * name_format:    The sprintf pattern to use for input names
   *  * form_formatter: The form formatter name (table and list are bundled)
   *
   * @param mixed $fields     Initial fields
   * @param array $options    An array of options
   * @param array $attributes An array of default HTML attributes
   * @param array $labels     An array of HTML labels
   * @param array $helps      An array of help texts
   *
   * @throws InvalidArgumentException when the passed fields not null or array
   *
   * @see sfWidgetForm
   */
  public function __construct($fields = null, $options = array(), $attributes = array(), $labels = array(), $helps = array())
  {
    $this->addOption('name_format', '%s');
    $this->addOption('form_formatter', null);

    parent::__construct($options, $attributes);

    if (is_array($fields))
    {
      foreach ($fields as $name => $widget)
      {
        $this[$name] = $widget;
      }
    }
    else if (null !== $fields)
    {
      throw new InvalidArgumentException('sfWidgetFormSchema constructor takes an array of sfWidget objects.');
    }

    $this->setLabels($labels);
    $this->helps = $helps;
  }

  /**
   * Sets the default value for a field.
   *
   * @param string $name  The field name
   * @param string $value The default value (required - the default value is here because PHP do not allow signature changes with inheritance)
   *
   * @return sfWidget The current widget instance
   */
  public function setDefault($name, $value = null)
  {
    $this[$name]->setDefault($value);

    return $this;
  }

  /**
   * Gets the default value of a field.
   *
   * @param string $name The field name (required - the default value is here because PHP do not allow signature changes with inheritance)
   *
   * @return string The default value
   */
  public function getDefault($name = null)
  {
    return $this[$name]->getDefault();
  }

  /**
   * Sets the default values for the widget.
   *
   * @param array $values The default values for the widget
   *
   * @return sfWidget The current widget instance
   */
  public function setDefaults(array $values)
  {
    foreach ($this->fields as $name => $widget)
    {
      if (array_key_exists($name, $values))
      {
        $widget->setDefault($values[$name]);
      }
    }

    return $this;
  }

  /**
   * Returns the defaults values for the widget schema.
   *
   * @return array An array of default values
   */
  public function getDefaults()
  {
    $defaults = array();

    foreach ($this->fields as $name => $widget)
    {
      $defaults[$name] = $widget instanceof sfWidgetFormSchema ? $widget->getDefaults() : $widget->getDefault();
    }

    return $defaults;
  }

  /**
   * Adds a form formatter.
   *
   * @param string                      $name      The formatter name
   * @param sfWidgetFormSchemaFormatter $formatter An sfWidgetFormSchemaFormatter instance
   *
   * @return sfWidget The current widget instance
   */
  public function addFormFormatter($name, sfWidgetFormSchemaFormatter $formatter)
  {
    $this->formFormatters[$name] = $formatter;

    return $this;
  }

  /**
   * Returns all the form formats defined for this form schema.
   *
   * @return array An array of named form formats
   */
  public function getFormFormatters()
  {
    return $this->formFormatters;
  }

  /**
   * Sets the generic default formatter name used by the class. If you want all
   * of your forms to be generated with the <code>list</code> format, you can
   * do it in a project or application configuration class:
   *
   * <pre>
   * class ProjectConfiguration extends sfProjectConfiguration
   * {
   *   public function setup()
   *   {
   *     sfWidgetFormSchema::setDefaultFormFormatterName('list');
   *   }
   * }
   * </pre>
   *
   * @param string $name New default formatter name
   */
  static public function setDefaultFormFormatterName($name)
  {
    self::$defaultFormatterName = $name;
  }

  /**
   * Sets the form formatter name to use when rendering the widget schema.
   *
   * @param string $name The form formatter name
   *
   * @return sfWidget The current widget instance
   */
  public function setFormFormatterName($name)
  {
    $this->options['form_formatter'] = $name;

    return $this;
  }

  /**
   * Gets the form formatter name that will be used to render the widget schema.
   *
   * @return string The form formatter name
   */
  public function getFormFormatterName()
  {
    return null === $this->options['form_formatter'] ? self::$defaultFormatterName : $this->options['form_formatter'];
  }

  /**
   * Returns the form formatter to use for widget schema rendering
   *
   * @return sfWidgetFormSchemaFormatter sfWidgetFormSchemaFormatter instance
   *
   * @throws InvalidArgumentException when the form formatter not exists
   */
  public function getFormFormatter()
  {
    $name = $this->getFormFormatterName();

    if (!isset($this->formFormatters[$name]))
    {
      $class = 'sfWidgetFormSchemaFormatter'.ucfirst($name);

      if (!class_exists($class))
      {
        throw new InvalidArgumentException(sprintf('The form formatter "%s" does not exist.', $name));
      }

      $this->formFormatters[$name] = new $class($this);
    }

    return $this->formFormatters[$name];
  }

  /**
   * Sets the format string for the name HTML attribute.
   *
   * If you are using the form framework with symfony, do not use a reserved word in the
   * name format.  If you do, symfony may act in an unexpected manner.
   *
   * For symfony 1.1+, the following words are reserved and must NOT be used as
   * the name format:
   *
   *  * module    (example: module[%s])
   *  * action    (example: action[%s])
   *
   * However, you CAN use other variations, such as actions[%s] (note the s).
   *
   * @param string $format The format string (must contain a %s for the name placeholder)
   *
   * @return sfWidget The current widget instance
   *
   * @throws InvalidArgumentException when no %s exists in the format
   */
  public function setNameFormat($format)
  {
    if (false !== $format && false === strpos($format, '%s'))
    {
      throw new InvalidArgumentException(sprintf('The name format must contain %%s ("%s" given)', $format));
    }

    $this->options['name_format'] = $format;

    return $this;
  }

  /**
   * Gets the format string for the name HTML attribute.
   *
   * @return string The format string
   */
  public function getNameFormat()
  {
    return $this->options['name_format'];
  }

  /**
   * Sets the label names to render for each field.
   *
   * @param array $labels  An array of label names
   *
   * @return sfWidget The current widget instance
   */
  public function setLabels(array $labels)
  {
    foreach ($this->fields as $name => $widget)
    {
      if (array_key_exists($name, $labels))
      {
        $widget->setLabel($labels[$name]);
      }
    }

    return $this;
  }

  /**
   * Gets the labels.
   *
   * @return array An array of label names
   */
  public function getLabels()
  {
    $labels = array();

    foreach ($this->fields as $name => $widget)
    {
      $labels[$name] = $widget->getLabel();
    }

    return $labels;
  }

  /**
   * Sets a label.
   *
   * @param string $name  The field name
   * @param string $value The label name (required - the default value is here because PHP do not allow signature changes with inheritance)
   *
   * @return sfWidget The current widget instance
   *
   * @throws InvalidArgumentException when you try to set a label on a none existing widget
   */
  public function setLabel($name, $value = null)
  {
    if (2 == func_num_args())
    {
      if (!isset($this->fields[$name]))
      {
        throw new InvalidArgumentException(sprintf('Unable to set the label on an unexistant widget ("%s").', $name));
      }

      $this->fields[$name]->setLabel($value);
    }
    else
    {
      // set the label for this widget schema
      parent::setLabel($name);
    }

    return $this;
  }

  /**
   * Gets a label by field name.
   *
   * @param  string $name  The field name (required - the default value is here because PHP do not allow signature changes with inheritance)
   *
   * @return string The label name or an empty string if it is not defined
   *
   * @throws InvalidArgumentException when you try to get a label for a none existing widget
   */
  public function getLabel($name = null)
  {
    if (1 == func_num_args())
    {
      if (!isset($this->fields[$name]))
      {
        throw new InvalidArgumentException(sprintf('Unable to get the label on an unexistant widget ("%s").', $name));
      }

      return $this->fields[$name]->getLabel();
    }
    else
    {
      // label for this widget schema
      return parent::getLabel();
    }
  }

  /**
   * Sets the help texts to render for each field.
   *
   * @param array $helps An array of help texts
   *
   * @return sfWidget The current widget instance
   */
  public function setHelps(array $helps)
  {
    $this->helps = $helps;

    return $this;
  }

  /**
   * Sets the help texts.
   *
   * @return array An array of help texts
   */
  public function getHelps()
  {
    return $this->helps;
  }

  /**
   * Sets a help text.
   *
   * @param string $name The field name
   * @param string $help The help text
   *
   * @return sfWidget The current widget instance
   */
  public function setHelp($name, $help)
  {
    $this->helps[$name] = $help;

    return $this;
  }

  /**
   * Gets a text help by field name.
   *
   * @param string $name The field name
   *
   * @return string The help text or an empty string if it is not defined
   */
  public function getHelp($name)
  {
    return array_key_exists($name, $this->helps) ? $this->helps[$name] : '';
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    $stylesheets = array();

    foreach ($this->fields as $field)
    {
      $stylesheets = array_merge($stylesheets, $field->getStylesheets());
    }

    return $stylesheets;
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $javascripts = array();

    foreach ($this->fields as $field)
    {
      $javascripts = array_merge($javascripts, $field->getJavaScripts());
    }

    return array_unique($javascripts);
  }

  /**
   * Returns true if the widget schema needs a multipart form.
   *
   * @return bool true if the widget schema needs a multipart form, false otherwise
   */
  public function needsMultipartForm()
  {
    foreach ($this->fields as $field)
    {
      if ($field->needsMultipartForm())
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Renders a field by name.
   *
   * @param string $name       The field name
   * @param string $value      The field value
   * @param array  $attributes An array of HTML attributes to be merged with the current HTML attributes
   * @param array  $errors     An array of errors for the field
   *
   * @return string An HTML string representing the rendered widget
   *
   * @throws InvalidArgumentException when the widget not exist
   */
  public function renderField($name, $value = null, $attributes = array(), $errors = array())
  {
    if (null === $widget = $this[$name])
    {
      throw new InvalidArgumentException(sprintf('The field named "%s" does not exist.', $name));
    }

    if ($errors && $widget instanceof sfWidgetFormSchema && !$errors instanceof sfValidatorErrorSchema)
    {
      $schema = new sfValidatorErrorSchema($errors->getValidator());
      $schema->addError($errors);
      $errors = $schema;
    }

    // we clone the widget because we want to change the id format temporarily
    $clone = clone $widget;
    $clone->setIdFormat($this->options['id_format']);

    return $clone->render($this->generateName($name), $value, array_merge($clone->getAttributes(), $attributes), $errors);
  }

  /**
   * Renders the widget.
   *
   * @param string $name       The name of the HTML widget
   * @param mixed  $values     The values of the widget
   * @param array  $attributes An array of HTML attributes
   * @param array  $errors     An array of errors
   *
   * @return string An HTML representation of the widget
   *
   * @throws InvalidArgumentException when values type is not array|ArrayAccess
   */
  public function render($name, $values = array(), $attributes = array(), $errors = array())
  {
    if (null === $values)
    {
      $values = array();
    }

    if (!is_array($values) && !$values instanceof ArrayAccess)
    {
      throw new InvalidArgumentException('You must pass an array of values or an instanceof ArrayAccess to render a widget schema');
    }

    $formFormat = $this->getFormFormatter();

    $rows = array();
    $hiddenRows = array();
    $errorRows = array();

    // render each field
    foreach ($this->positions as $name)
    {
      $widget = $this[$name];
      $value = isset($values[$name]) ? $values[$name] : null;
      $error = isset($errors[$name]) ? $errors[$name] : array();
      $widgetAttributes = isset($attributes[$name]) ? $attributes[$name] : array();

      if ($widget instanceof sfWidgetForm && $widget->isHidden())
      {
        $hiddenRows[] = $this->renderField($name, $value, $widgetAttributes);
      }
      else
      {
        $field = $this->renderField($name, $value, $widgetAttributes, $error);

        // don't add a label tag and errors if we embed a form schema
        $label = $widget instanceof sfWidgetFormSchema ? $formFormat->generateLabelName($name) : $formFormat->generateLabel($name);
        $error = $widget instanceof sfWidgetFormSchema ? array() : $error;

        $rows[] = $formFormat->formatRow($label, $field, $error, $this->getHelp($name));
      }
    }

    if ($rows)
    {
      // insert hidden fields in the last row
      for ($i = 0, $max = count($rows); $i < $max; $i++)
      {
        $rows[$i] = strtr($rows[$i], array('%hidden_fields%' => $i == $max - 1 ? implode("\n", $hiddenRows) : ''));
      }
    }
    else
    {
      // only hidden fields
      $rows[0] = implode("\n", $hiddenRows);
    }

    return $formFormat->formatErrorRow($this->getGlobalErrors($errors)).implode('', $rows);
  }

  /**
   * Gets errors that need to be included in global errors.
   *
   * @param array $errors An array of errors
   *
   * @return string An HTML representation of global errors for the widget
   */
  public function getGlobalErrors($errors)
  {
    $globalErrors = array();

    // global errors and errors for non existent fields
    if (null !== $errors)
    {
      foreach ($errors as $name => $error)
      {
        if (!isset($this->fields[$name]))
        {
          $globalErrors[] = $error;
        }
      }
    }

    // errors for hidden fields
    foreach ($this->positions as $name)
    {
      /** @var $this sfWidgetForm[] */
      if ($this[$name] instanceof sfWidgetForm && $this[$name]->isHidden())
      {
        if (isset($errors[$name]))
        {
          $globalErrors[$this->getFormFormatter()->generateLabelName($name)] = $errors[$name];
        }
      }
    }

    return $globalErrors;
  }

  /**
   * Generates a name.
   *
   * @param string $name The name
   *
   * @return string The generated name
   */
  public function generateName($name)
  {
    $format = $this->getNameFormat();

    if ('[%s]' == substr($format, -4) && preg_match('/^(.+?)\[(.+)\]$/', $name, $match))
    {
      $name = sprintf('%s[%s][%s]', substr($format, 0, -4), $match[1], $match[2]);
    }
    else if (false !== $format)
    {
      $name = sprintf($format, $name);
    }

    if ($parent = $this->getParent())
    {
      $name = $parent->generateName($name);
    }

    return $name;
  }

  /**
   * Returns true if the schema has a field with the given name (implements the ArrayAccess interface).
   *
   * @param string $name The field name
   *
   * @return bool true if the schema has a field with the given name, false otherwise
   */
  public function offsetExists($name)
  {
    return isset($this->fields[$name]);
  }

  /**
   * Gets the field associated with the given name (implements the ArrayAccess interface).
   *
   * @param string $name The field name
   *
   * @return sfWidget|null The sfWidget instance associated with the given name, null if it does not exist
   */
  public function offsetGet($name)
  {
    return isset($this->fields[$name]) ? $this->fields[$name] : null;
  }

  /**
   * Sets a field (implements the ArrayAccess interface).
   *
   * @param string   $name   The field name
   * @param sfWidget $widget An sfWidget instance
   *
   * @throws InvalidArgumentException when the field is not instance of sfWidget
   */
  public function offsetSet($name, $widget)
  {
    if (!$widget instanceof sfWidget)
    {
      throw new InvalidArgumentException('A field must be an instance of sfWidget.');
    }

    if (!isset($this->fields[$name]))
    {
      $this->positions[] = (string) $name;
    }

    $this->fields[$name] = clone $widget;
    $this->fields[$name]->setParent($this);

    if ($widget instanceof sfWidgetFormSchema)
    {
      $this->fields[$name]->setNameFormat($name.'[%s]');
    }
  }

  /**
   * Removes a field by name (implements the ArrayAccess interface).
   *
   * @param string $name field name
   */
  public function offsetUnset($name)
  {
    unset($this->fields[$name]);
    if (false !== $position = array_search((string) $name, $this->positions))
    {
      unset($this->positions[$position]);

      $this->positions = array_values($this->positions);
    }
  }

  /**
   * Returns an array of fields.
   *
   * @return sfWidget[] An array of sfWidget instance
   */
  public function getFields()
  {
    return $this->fields;
  }

  /**
   * Gets the positions of the fields.
   *
   * The field positions are only used when rendering the schema with ->render().
   *
   * @return array An ordered array of field names
   */
  public function getPositions()
  {
    return $this->positions;
  }

  /**
   * Sets the positions of the fields.
   *
   * @param array $positions An ordered array of field names
   *
   * @return sfWidget The current widget instance
   *
   * @throws InvalidArgumentException when not all fields set in $positions
   *
   * @see getPositions()
   */
  public function setPositions(array $positions)
  {
    $positions = array_unique(array_values($positions));
    $current   = array_keys($this->fields);

    if ($diff = array_diff($positions, $current))
    {
      throw new InvalidArgumentException('Widget schema does not include the following field(s): '.implode(', ', $diff));
    }

    if ($diff = array_diff($current, $positions))
    {
      throw new InvalidArgumentException('Positions array must include all fields. Missing: '.implode(', ', $diff));
    }

    foreach ($positions as &$position)
    {
      $position = (string) $position;
    }

    $this->positions = $positions;

    return $this;
  }

  /**
   * Moves a field in a given position
   *
   * Available actions are:
   *
   *  * sfWidgetFormSchema::BEFORE
   *  * sfWidgetFormSchema::AFTER
   *  * sfWidgetFormSchema::LAST
   *  * sfWidgetFormSchema::FIRST
   *
   * @param string   $field  The field name to move
   * @param string   $action The action (see above for all possible actions)
   * @param string   $pivot  The field name used for AFTER and BEFORE actions
   *
   * @throws InvalidArgumentException when field not exist
   * @throws InvalidArgumentException when relative field not exist
   * @throws LogicException           when you try to move a field without a relative field
   * @throws LogicException           when the $action not exist
   */
  public function moveField($field, $action, $pivot = null)
  {
    $field = (string) $field;
    if (false === $fieldPosition = array_search($field, $this->positions))
    {
      throw new InvalidArgumentException(sprintf('Field "%s" does not exist.', $field));
    }
    unset($this->positions[$fieldPosition]);
    $this->positions = array_values($this->positions);

    if (null !== $pivot)
    {
      $pivot = (string) $pivot;
      if (false === $pivotPosition = array_search($pivot, $this->positions))
      {
        throw new InvalidArgumentException(sprintf('Field "%s" does not exist.', $pivot));
      }
    }

    switch ($action)
    {
      case sfWidgetFormSchema::FIRST:
        array_unshift($this->positions, $field);
        break;
      case sfWidgetFormSchema::LAST:
        $this->positions []= $field;
        break;
      case sfWidgetFormSchema::BEFORE:
        if (null === $pivot)
        {
          throw new LogicException(sprintf('Unable to move field "%s" without a relative field.', $field));
        }
        $this->positions = array_merge(
          array_slice($this->positions, 0, $pivotPosition),
          array($field),
          array_slice($this->positions, $pivotPosition)
        );
        break;
      case sfWidgetFormSchema::AFTER:
        if (null === $pivot)
        {
          throw new LogicException(sprintf('Unable to move field "%s" without a relative field.', $field));
        }
        $this->positions = array_merge(
          array_slice($this->positions, 0, $pivotPosition + 1),
          array($field),
          array_slice($this->positions, $pivotPosition + 1)
        );
        break;
      default:
        throw new LogicException(sprintf('Unknown move operation for field "%s".', $field));
    }
  }

  public function __clone()
  {
    foreach ($this->fields as $name => $field)
    {
      // offsetSet will clone the field and change the parent
      $this[$name] = $field;
    }

    foreach ($this->formFormatters as &$formFormatter)
    {
      $formFormatter = clone $formFormatter;
      $formFormatter->setWidgetSchema($this);
    }
  }
}
