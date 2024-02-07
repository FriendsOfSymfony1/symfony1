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
 * Output escaping decorator class for arrays.
 *
 * @see        \sfOutputEscaper
 *
 * @author     Mike Squire <mike@somosis.co.uk>
 *
 * @version    SVN: $Id$
 */
class sfOutputEscaperArrayDecorator extends \sfOutputEscaperGetterDecorator implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * Used by the iterator to know if the current element is valid.
     *
     * @var int
     */
    private $count;

    /**
     * Constructor.
     *
     * @see \sfOutputEscaper
     */
    public function __construct($escapingMethod, $value)
    {
        parent::__construct($escapingMethod, $value);

        $this->count = count($this->value);
    }

    /**
     * Reset the array to the beginning (as required for the Iterator interface).
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->value);

        $this->count = count($this->value);
    }

    /**
     * Get the key associated with the current value (as required by the Iterator interface).
     *
     * @return string The key
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->value);
    }

    /**
     * Escapes and return the current value (as required by the Iterator interface).
     *
     * This escapes the value using {@link sfOutputEscaper::escape()} with
     * whatever escaping method is set for this instance.
     *
     * @return mixed The escaped value
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return \sfOutputEscaper::escape($this->escapingMethod, current($this->value));
    }

    /**
     * Moves to the next element (as required by the Iterator interface).
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->value);

        --$this->count;
    }

    /**
     * Returns true if the current element is valid (as required by the Iterator interface).
     *
     * The current element will not be valid if {@link next()} has fallen off the
     * end of the array or if there are no elements in the array and {@link * rewind()} was called.
     *
     * @return bool The validity of the current element; true if it is valid
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->count > 0;
    }

    /**
     * Returns true if the supplied offset isset in the array (as required by the ArrayAccess interface).
     *
     * @param string $offset The offset of the value to check existance of
     *
     * @return bool true if the offset isset; false otherwise
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    /**
     * Returns the element associated with the offset supplied (as required by the ArrayAccess interface).
     *
     * @param string $offset The offset of the value to get
     *
     * @return mixed The escaped value
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $value = isset($this->value[$offset]) ? $this->value[$offset] : null;

        return \sfOutputEscaper::escape($this->escapingMethod, $value);
    }

    /**
     * Throws an exception saying that values cannot be set (this method is
     * required for the ArrayAccess interface).
     *
     * This (and the other sfOutputEscaper classes) are designed to be read only
     * so this is an illegal operation.
     *
     * @param string $offset (ignored)
     * @param string $value  (ignored)
     *
     * @throws \sfException
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new \sfException('Cannot set values.');
    }

    /**
     * Throws an exception saying that values cannot be unset (this method is
     * required for the ArrayAccess interface).
     *
     * This (and the other sfOutputEscaper classes) are designed to be read only
     * so this is an illegal operation.
     *
     * @param string $offset (ignored)
     *
     * @throws \sfException
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new \sfException('Cannot unset values.');
    }

    /**
     * Returns the size of the array (are required by the Countable interface).
     *
     * @return int The size of the array
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->value);
    }

    /**
     * Returns the (unescaped) value from the array associated with the key supplied.
     *
     * @param string $key The key into the array to use
     *
     * @return mixed The value
     */
    public function getRaw($key)
    {
        return $this->value[$key];
    }
}
