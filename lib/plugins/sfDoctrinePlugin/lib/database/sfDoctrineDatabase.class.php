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
 * A symfony database driver for Doctrine.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineDatabase extends \sfDatabase
{
    /**
     * Instance of the Doctrine_Connection for this instance of sfDoctrineDatabase.
     * Connection can be accessed by the getDoctrineConnection() accessor method.
     *
     * @var \Doctrine_Connection
     */
    protected $_doctrineConnection;

    /**
     * @var \sfDoctrineConnectionProfiler
     */
    protected $profiler;

    /**
     * Initialize a sfDoctrineDatabase connection with the given parameters.
     *
     * <code>
     * $parameters = array(
     *    'name'       => 'doctrine',
     *    'dsn'        => 'sqlite:////path/to/sqlite/db');
     *
     * $p = new sfDoctrineDatabase($parameters);
     * </code>
     *
     * @param array $parameters Array of parameters used to initialize the database connection
     */
    public function initialize($parameters = [])
    {
        parent::initialize($parameters);

        if (null !== $this->_doctrineConnection) {
            return;
        }

        $dsn = $this->getParameter('dsn');
        $name = $this->getParameter('name');

        // Make sure we pass non-PEAR style DSNs as an array
        if (!strpos($dsn, '://')) {
            $dsn = [$dsn, $this->getParameter('username'), $this->getParameter('password')];
        }

        // Make the Doctrine connection for $dsn and $name
        $configuration = \sfProjectConfiguration::getActive();
        $dispatcher = $configuration->getEventDispatcher();
        $manager = \Doctrine_Manager::getInstance();

        $this->_doctrineConnection = $manager->openConnection($dsn, $name);

        $attributes = $this->getParameter('attributes', []);
        foreach ($attributes as $name => $value) {
            if (is_string($name)) {
                $stringName = $name;
                $name = constant('Doctrine_Core::ATTR_'.strtoupper($name));
            }

            if (is_string($value)) {
                $valueConstantName = 'Doctrine_Core::'.strtoupper($stringName).'_'.strtoupper($value);
                $value = defined($valueConstantName) ? constant($valueConstantName) : $value;
            }

            $this->_doctrineConnection->setAttribute($name, $value);
        }

        $encoding = $this->getParameter('encoding', 'UTF8');
        $eventListener = new \sfDoctrineConnectionListener($this->_doctrineConnection, $encoding);
        $this->_doctrineConnection->addListener($eventListener);

        // Load Query Profiler
        if ($this->getParameter('profiler', \sfConfig::get('sf_debug'))) {
            $this->profiler = new \sfDoctrineConnectionProfiler($dispatcher, [
                'logging' => $this->getParameter('logging', \sfConfig::get('sf_logging_enabled')),
            ]);
            $this->_doctrineConnection->addListener($this->profiler, 'symfony_profiler');
        }

        $dispatcher->notify(new \sfEvent($manager, 'doctrine.configure_connection', ['connection' => $this->_doctrineConnection, 'database' => $this]));
    }

    /**
     * Get the Doctrine_Connection instance.
     *
     * @return \Doctrine_Connection $conn
     */
    public function getDoctrineConnection()
    {
        return $this->_doctrineConnection;
    }

    /**
     * Returns the connection profiler.
     *
     * @return \sfDoctrineConnectionProfiler|null
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * Initializes the connection and sets it to object.
     */
    public function connect()
    {
        $this->connection = $this->_doctrineConnection->getDbh();
    }

    /**
     * Execute the shutdown procedure.
     */
    public function shutdown()
    {
        if (null !== $this->connection) {
            $this->connection = null;
        }
        if (null !== $this->_doctrineConnection) {
            $this->_doctrineConnection->getManager()->closeConnection($this->_doctrineConnection);
        }
    }
}
