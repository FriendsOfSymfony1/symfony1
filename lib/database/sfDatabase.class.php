<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDatabase is a base abstraction class that allows you to setup any type of
 * database connection via a configuration file.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 */
abstract class sfDatabase
{
    /** @var sfParameterHolder */
    protected $parameterHolder;

    /** @var PDO|resource */
    protected $connection;

    /** @var PDO|resource (It's interchangeable with. Can be dropped at all.) */
    protected $resource;

    /**
     * Class constructor.
     *
     * @see initialize()
     *
     * @param array $parameters An associative array of initialization parameters
     */
    public function __construct($parameters = [])
    {
        $this->initialize($parameters);
    }

    /**
     * Initializes this sfDatabase object.
     *
     * @param array $parameters An associative array of initialization parameters
     *
     * @throws sfInitializationException If an error occurs while initializing this sfDatabase object
     */
    public function initialize($parameters = [])
    {
        $this->parameterHolder = new sfParameterHolder();
        $this->parameterHolder->add($parameters);
    }

    /**
     * Connects to the database.
     *
     * @throws sfDatabaseException If a connection could not be created
     */
    abstract public function connect();

    /**
     * Retrieves the database connection associated with this sfDatabase implementation.
     *
     * When this is executed on a Database implementation that isn't an
     * abstraction layer, a copy of the resource will be returned.
     *
     * @return mixed A database connection
     *
     * @throws sfDatabaseException If a connection could not be retrieved
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connect();
        }

        return $this->connection;
    }

    /**
     * Retrieves a raw database resource associated with this sfDatabase implementation.
     *
     * @return mixed A database resource
     *
     * @throws sfDatabaseException If a resource could not be retrieved
     */
    public function getResource()
    {
        if (null === $this->resource) {
            $this->connect();
        }

        return $this->resource;
    }

    /**
     * Gets the parameter holder for this object.
     *
     * @return sfParameterHolder A sfParameterHolder instance
     */
    public function getParameterHolder()
    {
        return $this->parameterHolder;
    }

    /**
     * Gets the parameter associated with the given key.
     *
     * This is a shortcut for:
     *
     * <code>$this->getParameterHolder()->get()</code>
     *
     * @param string $name    The key name
     * @param string $default The default value
     *
     * @return string The value associated with the key
     *
     * @see sfParameterHolder
     */
    public function getParameter($name, $default = null)
    {
        return $this->parameterHolder->get($name, $default);
    }

    /**
     * Returns true if the given key exists in the parameter holder.
     *
     * This is a shortcut for:
     *
     * <code>$this->getParameterHolder()->has()</code>
     *
     * @param string $name The key name
     *
     * @return bool true if the given key exists, false otherwise
     *
     * @see sfParameterHolder
     */
    public function hasParameter($name)
    {
        return $this->parameterHolder->has($name);
    }

    /**
     * Sets the value for the given key.
     *
     * This is a shortcut for:
     *
     * <code>$this->getParameterHolder()->set()</code>
     *
     * @param string $name  The key name
     * @param string $value The value
     *
     * @see sfParameterHolder
     */
    public function setParameter($name, $value)
    {
        $this->parameterHolder->set($name, $value);
    }

    /**
     * Executes the shutdown procedure.
     *
     * @throws sfDatabaseException If an error occurs while shutting down this database
     */
    abstract public function shutdown();
}
