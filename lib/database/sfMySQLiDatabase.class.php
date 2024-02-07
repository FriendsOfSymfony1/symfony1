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
 * sfMySQLiDatabase provides connectivity for the MySQL brand database.
 *
 * @see \sfMySQLDatabase
 *
 * @property $connection mysqli
 */
class sfMySQLiDatabase extends \sfMySQLDatabase
{
    /**
     * @throws \sfDatabaseException
     */
    public function connect()
    {
        // PHP 8.1 Activate Exception per default, revert behavior to "return false"
        mysqli_report(MYSQLI_REPORT_OFF);

        parent::connect();
    }

    /**
     * Execute the shutdown procedure.
     *
     * @throws \sfDatabaseException If an error occurs while shutting down this database
     */
    public function shutdown()
    {
        if (null != $this->connection) {
            @mysqli_close($this->connection);
        }
    }

    /**
     * Returns the appropriate connect method.
     *
     * @param bool $persistent whether persistent connections are use or not
     *                         The MySQLi driver does not support persistent
     *                         connections so this argument is ignored
     *
     * @return string name of connect method
     */
    protected function getConnectMethod($persistent)
    {
        return 'mysqli_connect';
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
        return null != $database && !@mysqli_select_db($this->connection, $database);
    }
}
