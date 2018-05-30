<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorErrorSchema represents a validation schema error.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfValidatorErrorSchema extends sfValidatorError implements ArrayAccess, Iterator, Countable
{
  protected
    $errors       = array(),
    $globalErrors = array(),
    $namedErrors  = array(),
    $count        = 0;

  /**
   * Constructor.
   *
   * @param sfValidatorBase $validator  An sfValidatorBase instance
   * @param array           $errors     An array of errors, depreciated
   */
  public function __construct(sfValidatorBase $validator, $errors = array())
  {
    $this->validator = $validator;
    $this->arguments = array();

    // override default exception message and code
    $this->code    = '';
    $this->message = '';

    foreach ($errors as $name => $error)
    {
      $this->addError($error, is_numeric($name) ? null : $name);
    }
  }

  /**
   * Adds an error.
   *
   * This method merges sfValidatorErrorSchema errors with the current instance.
   *
   * @param sfValidatorError $error  An sfValidatorError instance
   * @param string           $name   The error name
   *
   * @return sfValidatorErrorSchema The current error schema instance
   */
  public function addError(sfValidatorError $error, $name = null)
  {
    if (null === $name)
    {
      if ($error instanceof sfValidatorErrorSchema)
      {
        $this->addErrors($error);
      }
      else
      {
        $this->globalErrors[] = $error;
        $this->errors[] = $error;
      }
    }
    else if (isset($this->namedErrors[$name]))
    {
      if (!$this->namedErrors[$name] instanceof sfValidatorErrorSchema)
      {
        $current = $this->namedErrors[$name];
        $this->namedErrors[$name] = new sfValidatorErrorSchema($current->getValidator());
        $this->namedErrors[$name]->addError($current);
      }

      $method = $error instanceof sfValidatorErrorSchema ? 'addErrors' : 'addError';
      $this->namedErrors[$name]->$method($error);

      $this->errors[$name] = $this->namedErrors[$name];
    }
    else
    {
      $this->namedErrors[$name] = $error;
      $this->errors[$name] = $error;
    }

    $this->updateCode();
    $this->updateMessage();

    return $this;
  }

  /**
   * Adds a collection of errors.
   *
   * @param sfValidatorErrorSchema $errorsAn sfValidatorErrorSchema instance
   *
   * @return sfValidatorErrorSchema The current error schema instance
   */
  public function addErrors(sfValidatorErrorSchema $errors)
  {
    foreach ($errors->getGlobalErrors() as $error)
    {
      $this->addError($error);
    }

    foreach ($errors->getNamedErrors() as $name => $error)
    {
      $this->addError($error, $name);
    }

    return $this;
  }

  /**
   * Gets an array of all errors
   *
   * @return array An array of sfValidatorError instances
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Gets an array of all named errors
   *
   * @return sfValidatorError[] An array of sfValidatorError instances
   */
  public function getNamedErrors()
  {
    return $this->namedErrors;
  }

  /**
   * Gets an array of all global errors
   *
   * @return array An array of sfValidatorError instances
   */
  public function getGlobalErrors()
  {
    return $this->globalErrors;
  }

  /**
   * @see sfValidatorError
   */
  public function getValue()
  {
    return null;
  }

  /**
   * @see sfValidatorError
   */
  public function getArguments($raw = false)
  {
    return array();
  }

  /**
   * @see sfValidatorError
   */
  public function getMessageFormat()
  {
    return '';
  }

  /**
   * Returns the number of errors (implements the Countable interface).
   *
   * @return int The number of array
   */
  public function count()
  {
    return count($this->errors);
  }

  /**
   * Reset the error array to the beginning (implements the Iterator interface).
   */
  public function rewind()
  {
    reset($this->errors);

    $this->count = count($this->errors);
  }

  /**
   * Get the key associated with the current error (implements the Iterator interface).
   *
   * @return string The key
   */
  public function key()
  {
    return key($this->errors);
  }

  /**
   * Returns the current error (implements the Iterator interface).
   *
   * @return mixed The escaped value
   */
  public function current()
  {
    return current($this->errors);
  }

  /**
   * Moves to the next error (implements the Iterator interface).
   */
  public function next()
  {
    next($this->errors);

    --$this->count;
  }

  /**
   * Returns true if the current error is valid (implements the Iterator interface).
   *
   * @return boolean The validity of the current element; true if it is valid
   */
  public function valid()
  {
    return $this->count > 0;
  }

  /**
   * Returns true if the error exists (implements the ArrayAccess interface).
   *
   * @param  string $name  The name of the error
   *
   * @return bool true if the error exists, false otherwise
   */
  public function offsetExists($name)
  {
    return isset($this->errors[$name]);
  }

  /**
   * Returns the error associated with the name (implements the ArrayAccess interface).
   *
   * @param  string $name  The offset of the value to get
   *
   * @return sfValidatorError A sfValidatorError instance
   */
  public function offsetGet($name)
  {
    return isset($this->errors[$name]) ? $this->errors[$name] : null;
  }

  /**
   * Throws an exception saying that values cannot be set (implements the ArrayAccess interface).
   *
   * @param string $offset  (ignored)
   * @param string $value   (ignored)
   *
   * @throws LogicException
   */
  public function offsetSet($offset, $value)
  {
    throw new LogicException('Unable update an error.');
  }

  /**
   * Impossible to call because this is an exception!
   *
   * @param string $offset  (ignored)
   */
  public function offsetUnset($offset)
  {
  }

  /**
   * Updates the exception error code according to the current errors.
   */
  protected function updateCode()
  {
    $this->code = implode(' ', array_merge(
      array_map(function($e) { /** @var $e sfValidatorError */ return $e->getCode(); }, $this->globalErrors),
      array_map(function($n, $e) { /** @var $e sfValidatorError */ return $n.' ['.$e->getCode().']'; }, array_keys($this->namedErrors), array_values($this->namedErrors))
    ));
  }

  /**
   * Updates the exception error message according to the current errors.
   */
  protected function updateMessage()
  {
    $this->message = implode(' ', array_merge(
      array_map(function($e) { /** @var $e sfValidatorError */ return $e->getMessage(); }, $this->globalErrors),
      array_map(function($n, $e) { /** @var $e sfValidatorError */ return $n.' ['.$e->getMessage().']'; }, array_keys($this->namedErrors), array_values($this->namedErrors))
    ));
  }

  /**
   * Serializes the current instance.
   *
   * @return string The instance as a serialized string
   */
  public function serialize()
  {
    return serialize(array($this->validator, $this->arguments, $this->code, $this->message, $this->errors, $this->globalErrors, $this->namedErrors));
  }

  /**
   * Unserializes a sfValidatorError instance.
   *
   * @param string $serialized  A serialized sfValidatorError instance
   *
   */
  public function unserialize($serialized)
  {
    list($this->validator, $this->arguments, $this->code, $this->message, $this->errors, $this->globalErrors, $this->namedErrors) = unserialize($serialized);
  }
}
