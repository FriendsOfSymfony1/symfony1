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
 * sfMySQLiDatabase provides connectivity for the MySQL brand database.
 *
 * @property $connection mysqli
 */
class sfMySQLiDatabase extends sfDatabase
{
  /**
   * Connects to the database.
   *
   * @throws <b>sfDatabaseException</b> If a connection could not be created
   */
  public function connect()
  {
    $database = $this->getParameter('database');
    $host     = $this->getParameter('host', 'localhost');
    $password = $this->getParameter('password');
    $username = $this->getParameter('username');
    $encoding = $this->getParameter('encoding');

    // let's see if we need a persistent connection
    $connect = 'mysqli_connect';
    if ($password == null)
    {
      if ($username == null)
      {
        $this->connection = @$connect($host);
      }
      else
      {
        $this->connection = @$connect($host, $username);
      }
    }
    else
    {
      $this->connection = @$connect($host, $username, $password);
    }

    // make sure the connection went through
    if ($this->connection === false)
    {
      // the connection's foobar'd
      throw new sfDatabaseException('Failed to create a MySQLiDatabase connection.');
    }

    // select our database
    if ($this->selectDatabase($database))
    {
      // can't select the database
      throw new sfDatabaseException(sprintf('Failed to select MySQLiDatabase "%s".', $database));
    }

    // set encoding if specified
    if ($encoding)
    {
      @mysqli_query($this->connection, "SET NAMES '{$encoding}'");
    }

    // since we're not an abstraction layer, we copy the connection
    // to the resource
    $this->resource = $this->connection;
  }

  /**
   * Selects the database to be used in this connection
   *
   * @param string $database Name of database to be connected
   *
   * @return bool true if this was successful
   */
  protected function selectDatabase($database)
  {
   return ($database != null && !@mysqli_select_db($this->connection, $database));
  }

  /**
   * Execute the shutdown procedure
   *
   * @throws <b>sfDatabaseException</b> If an error occurs while shutting down this database
   */
  public function shutdown()
  {
    if ($this->connection != null)
    {
      @mysqli_close($this->connection);
    }
  }
}
