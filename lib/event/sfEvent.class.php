<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfEvent.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id: sfEvent.class.php 8698 2008-04-30 16:35:28Z fabien $
 */
class sfEvent implements ArrayAccess
{
    protected $value;
    protected $processed = false;
    protected $subject;
    protected $name = '';
    protected $parameters;

    /**
     * Constructs a new sfEvent.
     *
     * @param mixed  $subject    The subject
     * @param string $name       The event name
     * @param array  $parameters An array of parameters
     */
    public function __construct($subject, $name, $parameters = [])
    {
        $this->subject = $subject;
        $this->name = $name;

        $this->parameters = $parameters;
    }

    /**
     * Returns the subject.
     *
     * @return mixed The subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Returns the event name.
     *
     * @return string The event name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the return value for this event.
     *
     * @param mixed $value The return value
     */
    public function setReturnValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the return value.
     *
     * @return mixed The return value
     */
    public function getReturnValue()
    {
        return $this->value;
    }

    /**
     * Sets the processed flag.
     *
     * @param bool $processed The processed flag value
     */
    public function setProcessed($processed)
    {
        $this->processed = (bool) $processed;
    }

    /**
     * Returns whether the event has been processed by a listener or not.
     *
     * @return bool true if the event has been processed, false otherwise
     */
    public function isProcessed()
    {
        return $this->processed;
    }

    /**
     * Returns the event parameters.
     *
     * @return array The event parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Returns true if the parameter exists (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     *
     * @return bool true if the parameter exists, false otherwise
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * Returns a parameter value (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     *
     * @return mixed The parameter value
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new InvalidArgumentException(sprintf('The event "%s" has no "%s" parameter.', $this->name, $name));
        }

        return $this->parameters[$name];
    }

    /**
     * Sets a parameter (implements the ArrayAccess interface).
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Removes a parameter (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($name)
    {
        unset($this->parameters[$name]);
    }
}
