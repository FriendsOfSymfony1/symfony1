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
 * Represents a command line argument.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfCommandArgument
{
    public const REQUIRED = 1;
    public const OPTIONAL = 2;

    public const IS_ARRAY = 4;

    protected $name;
    protected $mode;
    protected $default;
    protected $help = '';

    /**
     * Constructor.
     *
     * @param string $name    The argument name
     * @param int    $mode    The argument mode: self::REQUIRED or self::OPTIONAL
     * @param string $help    A help text
     * @param mixed  $default The default value (for self::OPTIONAL mode only)
     *
     * @throws \sfCommandException
     */
    public function __construct($name, $mode = null, $help = '', $default = null)
    {
        if (null === $mode) {
            $mode = self::OPTIONAL;
        } elseif (is_string($mode) || $mode > 7) {
            throw new \sfCommandException(sprintf('Argument mode "%s" is not valid.', $mode));
        }

        $this->name = $name;
        $this->mode = $mode;
        $this->help = $help;

        $this->setDefault($default);
    }

    /**
     * Returns the argument name.
     *
     * @return string The argument name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns true if the argument is required.
     *
     * @return bool true if parameter mode is self::REQUIRED, false otherwise
     */
    public function isRequired()
    {
        return self::REQUIRED === (self::REQUIRED & $this->mode);
    }

    /**
     * Returns true if the argument can take multiple values.
     *
     * @return bool true if mode is self::IS_ARRAY, false otherwise
     */
    public function isArray()
    {
        return self::IS_ARRAY === (self::IS_ARRAY & $this->mode);
    }

    /**
     * Sets the default value.
     *
     * @param mixed $default The default value
     *
     * @throws \sfCommandException
     */
    public function setDefault($default = null)
    {
        if (self::REQUIRED === $this->mode && null !== $default) {
            throw new \sfCommandException('Cannot set a default value except for sfCommandParameter::OPTIONAL mode.');
        }

        if ($this->isArray()) {
            if (null === $default) {
                $default = [];
            } elseif (!is_array($default)) {
                throw new \sfCommandException('A default value for an array argument must be an array.');
            }
        }

        $this->default = $default;
    }

    /**
     * Returns the default value.
     *
     * @return mixed The default value
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Returns the help text.
     *
     * @return string The help text
     */
    public function getHelp()
    {
        return $this->help;
    }
}
