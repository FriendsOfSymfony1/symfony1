<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Abstract class that provides an interface for escaping of output.
 *
 * @author     Mike Squire <mike@somosis.co.uk>
 *
 * @version    SVN: $Id$
 */
abstract class sfOutputEscaper
{
    /**
     * The value that is to be escaped.
     */
    protected $value;

    /**
     * The escaping method that is going to be applied to the value and its
     * children. This is actually the name of a PHP callable.
     *
     * @var string
     */
    protected $escapingMethod;

    protected static $safeClasses = [];

    /**
     * Constructor stores the escaping method and value.
     *
     * Since sfOutputEscaper is an abstract class, instances cannot be created
     * directly but the constructor will be inherited by sub-classes.
     *
     * @param string $escapingMethod Escaping method
     * @param string $value          Escaping value
     */
    public function __construct($escapingMethod, $value)
    {
        $this->value = $value;
        $this->escapingMethod = $escapingMethod;
    }

    /**
     * Gets a value from the escaper.
     *
     * @param string $var Value to get
     *
     * @return mixed Value
     */
    public function __get($var)
    {
        return $this->escape($this->escapingMethod, $this->value->{$var});
    }

    /**
     * Decorates a PHP variable with something that will escape any data obtained
     * from it.
     *
     * The following cases are dealt with:
     *
     *    - The value is null or false: null or false is returned.
     *    - The value is scalar: the result of applying the escaping method is
     *      returned.
     *    - The value is an array or an object that implements the ArrayAccess
     *      interface: the array is decorated such that accesses to elements yield
     *      an escaped value.
     *    - The value implements the Traversable interface (either an Iterator, an
     *      IteratorAggregate or an internal PHP class that implements
     *      Traversable): decorated much like the array.
     *    - The value is another type of object: decorated such that the result of
     *      method calls is escaped.
     *
     * The escaping method is actually the name of a PHP callable. There are a set
     * of standard escaping methods listed in the escaping helper
     * (EscapingHelper.php).
     *
     * @param string $escapingMethod The escaping method (a PHP callable) to apply to the value
     * @param mixed  $value          The value to escape
     *
     * @return mixed Escaping value
     *
     * @throws InvalidArgumentException If the escaping fails
     */
    public static function escape($escapingMethod, $value)
    {
        if (null === $value) {
            return $value;
        }

        // Scalars are anything other than arrays, objects and resources.
        if (is_scalar($value)) {
            return call_user_func($escapingMethod, $value);
        }

        if (is_array($value)) {
            return new sfOutputEscaperArrayDecorator($escapingMethod, $value);
        }

        if (is_object($value)) {
            if ($value instanceof sfOutputEscaper) {
                // avoid double decoration
                $copy = clone $value;

                $copy->escapingMethod = $escapingMethod;

                return $copy;
            }
            if (self::isClassMarkedAsSafe(get_class($value))) {
                // the class or one of its children is marked as safe
                // return the unescaped object
                return $value;
            }
            if ($value instanceof sfOutputEscaperSafe) {
                // do not escape objects marked as safe
                // return the original object
                return $value->getValue();
            }
            if ($value instanceof Traversable) {
                return new sfOutputEscaperIteratorDecorator($escapingMethod, $value);
            }

            return new sfOutputEscaperObjectDecorator($escapingMethod, $value);
        }

        // it must be a resource; cannot escape that.
        throw new InvalidArgumentException(sprintf('Unable to escape value "%s".', var_export($value, true)));
    }

    /**
     * Unescapes a value that has been escaped previously with the escape() method.
     *
     * @param mixed $value The value to unescape
     *
     * @return mixed Unescaped value
     *
     * @throws InvalidArgumentException If the escaping fails
     */
    public static function unescape($value)
    {
        if (null === $value || is_bool($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return html_entity_decode($value, ENT_QUOTES, sfConfig::get('sf_charset'));
        }
        if (is_array($value)) {
            foreach ($value as $name => $v) {
                $value[$name] = self::unescape($v);
            }

            return $value;
        }
        if (is_object($value)) {
            return $value instanceof sfOutputEscaper ? $value->getRawValue() : $value;
        }

        return $value;
    }

    /**
     * Returns true if the class if marked as safe.
     *
     * @param string $class A class name
     *
     * @return bool true if the class if safe, false otherwise
     */
    public static function isClassMarkedAsSafe($class)
    {
        if (in_array($class, self::$safeClasses)) {
            return true;
        }

        foreach (self::$safeClasses as $safeClass) {
            if (is_subclass_of($class, $safeClass)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Marks an array of classes (and all its children) as being safe for output.
     *
     * @param array $classes An array of class names
     */
    public static function markClassesAsSafe(array $classes)
    {
        self::$safeClasses = array_unique(array_merge(self::$safeClasses, $classes));
    }

    /**
     * Marks a class (and all its children) as being safe for output.
     *
     * @param string $class A class name
     */
    public static function markClassAsSafe($class)
    {
        self::markClassesAsSafe([$class]);
    }

    /**
     * Returns the raw value associated with this instance.
     *
     * Concrete instances of sfOutputEscaper classes decorate a value which is
     * stored by the constructor. This returns that original, unescaped, value.
     *
     * @return mixed The original value used to construct the decorator
     */
    public function getRawValue()
    {
        return $this->value;
    }
}
