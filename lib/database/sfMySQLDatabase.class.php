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
 * sfMySQLDatabase provides connectivity for the MySQL brand database.
 *
 * <b>Optional parameters:</b>
 *
 * # <b>database</b>   - [none]      - The database name.
 * # <b>host</b>       - [localhost] - The database host.
 * # <b>username</b>   - [none]      - The database username.
 * # <b>password</b>   - [none]      - The database password.
 * # <b>persistent</b> - [No]        - Indicates that the connection should be persistent.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 */
class sfMySQLDatabase extends sfDatabase
{
    /**
     * Connects to the database.
     *
     * @throws sfDatabaseException If a connection could not be created
     */
    public function connect()
    {
        $database = $this->getParameter('database');
        $host = $this->getParameter('host', 'localhost');
        $password = $this->getParameter('password');
        $username = $this->getParameter('username');
        $encoding = $this->getParameter('encoding');

        // let's see if we need a persistent connection
        $connect = $this->getConnectMethod($this->getParameter('persistent', false));
        if (null == $password) {
            if (null == $username) {
                $this->connection = @$connect($host);
            } else {
                $this->connection = @$connect($host, $username);
            }
        } else {
            $this->connection = @$connect($host, $username, $password);
        }

        // make sure the connection went through
        if (false === $this->connection) {
            // the connection's foobar'd
            throw new sfDatabaseException('Failed to create a MySQLDatabase connection.');
        }

        // select our database
        if ($this->selectDatabase($database)) {
            // can't select the database
            throw new sfDatabaseException(sprintf('Failed to select MySQLDatabase "%s".', $database));
        }

        // set encoding if specified
        if ($encoding) {
            @mysql_query("SET NAMES '".$encoding."'", $this->connection);
        }

        // since we're not an abstraction layer, we copy the connection
        // to the resource
        $this->resource = $this->connection;
    }

    /**
     * Execute the shutdown procedure.
     *
     * @throws sfDatabaseException If an error occurs while shutting down this database
     */
    public function shutdown()
    {
        if (null != $this->connection) {
            @mysql_close($this->connection);
        }
    }

    /**
     * Returns the appropriate connect method.
     *
     * @param bool $persistent wether persistent connections are use or not
     *
     * @return string name of connect method
     */
    protected function getConnectMethod($persistent)
    {
        return $persistent ? 'mysql_pconnect' : 'mysql_connect';
    }

    /**
     * Selects the database to be used in this connection.
     *
     * @param string $database Name of database to be connected
     *
     * @return bool true if this was successful
     */
    protected function selectDatabase($database)
    {
        return null != $database && !@mysql_select_db($database, $this->connection);
    }
}
