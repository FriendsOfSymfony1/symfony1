<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfFormFieldSchema represents an array of widgets bind to names and values.
 *
 * @package    symfony
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfFormFieldSchema extends sfFormField implements ArrayAccess, Iterator, Countable
{
  protected
    $count      = 0,
    $fieldNames = array(),
    $fields     = array();

  /**
   * Constructor.
   *
   * @param sfWidgetFormSchema $widget A sfWidget instance
   * @param sfFormField        $parent The sfFormField parent instance (null for the root widget)
   * @param string             $name   The field name
   * @param string             $value  The field value
   * @param sfValidatorError   $error  A sfValidatorError instance
   */
  public function __construct(sfWidgetFormSchema $widget, sfFormField $parent = null, $name, $value, sfValidatorError $error = null)
  {
    parent::__construct($widget, $parent, $name, $value, $error);

    $this->fieldNames = $widget->getPositions();
  }

  /**
   * Renders hidden form fields.
   *
   * @param boolean $recursive False will prevent hidden fields from embedded forms from rendering
   *
   * @return string
   */
  public function renderHiddenFields($recursive = true)
  {
    $output = '';

    foreach ($this->getHiddenFields($recursive) as $field)
    {
      $output .= $field->render();
    }

    return $output;
  }

  /**
   * Returns an array of hidden fields from the current schema.
   *
   * @param boolean $recursive Whether to recur through embedded schemas
   *
   * @return array
   */
  public function getHiddenFields($recursive = true)
  {
    $fields = array();

    foreach ($this as $name => $field)
    {
      if ($field instanceof sfFormFieldSchema && $recursive)
      {
        $fields = array_merge($fields, $field->getHiddenFields($recursive));
      }
      else if ($field->isHidden())
      {
        $fields[] = $field;
      }
    }

    return $fields;
  }

  /**
   * Returns true if the bound field exists (implements the ArrayAccess interface).
   *
   * @param string $name The name of the bound field
   *
   * @return Boolean true if the widget exists, false otherwise
   */
  public function offsetExists($name)
  {
    return isset($this->widget[$name]);
  }

  /**
   * Returns the form field associated with the name (implements the ArrayAccess interface).
   *
   * @param string $name The offset of the value to get
   *
   * @return sfFormField A form field instance
   */
  public function offsetGet($name)
  {
    if (!isset($this->fields[$name]))
    {
      if (null === $widget = $this->widget[$name])
      {
        throw new InvalidArgumentException(sprintf('Widget "%s" does not exist.', $name));
      }

      $error = isset($this->error[$name]) ? $this->error[$name] : null;

      if ($widget instanceof sfWidgetFormSchema)
      {
        $class = 'sfFormFieldSchema';

        if ($error && !$error instanceof sfValidatorErrorSchema)
        {
          $current = $error;
          $error = new sfValidatorErrorSchema($error->getValidator());
          $error->addError($current);
        }
      }
      else
      {
        $class = 'sfFormField';
      }

      $this->fields[$name] = new $class($widget, $this, $name, isset($this->value[$name]) ? $this->value[$name] : null, $error);
    }

    return $this->fields[$name];
  }

  /**
   * Throws an exception saying that values cannot be set (implements the ArrayAccess interface).
   *
   * @param string $offset (ignored)
   * @param string $value (ignored)
   *
   * @throws LogicException
   */
  public function offsetSet($offset, $value)
  {
    throw new LogicException('Cannot update form fields (read-only).');
  }

  /**
   * Throws an exception saying that values cannot be unset (implements the ArrayAccess interface).
   *
   * @param string $offset (ignored)
   *
   * @throws LogicException
   */
  public function offsetUnset($offset)
  {
    throw new LogicException('Cannot remove form fields (read-only).');
  }

  /**
   * Resets the field names array to the beginning (implements the Iterator interface).
   */
  public function rewind()
  {
    reset($this->fieldNames);
    $this->count = count($this->fieldNames);
  }

  /**
   * Gets the key associated with the current form field (implements the Iterator interface).
   *
   * @return string The key
   */
  public function key()
  {
    return current($this->fieldNames);
  }

  /**
   * Returns the current form field (implements the Iterator interface).
   *
   * @return mixed The escaped value
   */
  public function current()
  {
    return $this[current($this->fieldNames)];
  }

  /**
   * Moves to the next form field (implements the Iterator interface).
   */
  public function next()
  {
    next($this->fieldNames);
    --$this->count;
  }

  /**
   * Returns true if the current form field is valid (implements the Iterator interface).
   *
   * @return boolean The validity of the current element; true if it is valid
   */
  public function valid()
  {
    return $this->count > 0;
  }

  /**
   * Returns the number of form fields (implements the Countable interface).
   *
   * @return integer The number of embedded form fields
   */
  public function count()
  {
    return count($this->fieldNames);
  }
}
