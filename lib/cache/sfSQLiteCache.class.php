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
 * Cache class that stores cached content in a SQLite database.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfSQLiteCache extends \sfCache
{
    protected $dbh;
    protected $sqlLiteVersion;
    protected $database = '';

    /**
     * Initializes this sfCache instance.
     *
     * Available options:
     *
     * * database: File where to put the cache database (or :memory: to store cache in memory)
     *
     * * see sfCache for options available for all drivers
     *
     * @see \sfCache
     */
    public function initialize($options = [])
    {
        if (!extension_loaded('SQLite') && !extension_loaded('pdo_SQLite')) {
            throw new \sfConfigurationException('sfSQLiteCache class needs "sqlite" or "pdo_sqlite" extension to be loaded.');
        }

        parent::initialize($options);

        if (!$this->getOption('database')) {
            throw new \sfInitializationException('You must pass a "database" option to initialize a sfSQLiteCache object.');
        }

        $this->setDatabase($this->getOption('database'));
    }

    /**
     * @see \sfCache
     *
     * @inheritdo
     *
     * @return SQLite3|SQLiteDatabase
     */
    public function getBackend()
    {
        return $this->dbh;
    }

    /**
     * @see \sfCache
     *
     * @param \mixed|null $default
     */
    public function get($key, $default = null)
    {
        if ($this->isSqLite3()) {
            $data = $this->dbh->querySingle(sprintf("SELECT data FROM cache WHERE key = '%s' AND timeout > %d", $this->dbh->escapeString($key), time()));
        } else {
            $data = $this->dbh->singleQuery(sprintf("SELECT data FROM cache WHERE key = '%s' AND timeout > %d", sqlite_escape_string($key), time()));
        }

        return null === $data ? $default : $data;
    }

    /**
     * @see \sfCache
     */
    public function has($key)
    {
        if ($this->isSqLite3()) {
            return (int) $this->dbh->querySingle(sprintf("SELECT count(*) FROM cache WHERE key = '%s' AND timeout > %d", $this->dbh->escapeString($key), time()));
        }

        return (bool) $this->dbh->query(sprintf("SELECT key FROM cache WHERE key = '%s' AND timeout > %d", sqlite_escape_string($key), time()))->numRows();
    }

    /**
     * @see \sfCache
     *
     * @param \mixed|null $lifetime
     */
    public function set($key, $data, $lifetime = null)
    {
        if ($this->getOption('automatic_cleaning_factor') > 0 && 1 == mt_rand(1, $this->getOption('automatic_cleaning_factor'))) {
            $this->clean(\sfCache::OLD);
        }

        if ($this->isSqLite3()) {
            return $this->dbh->exec(sprintf("INSERT OR REPLACE INTO cache (key, data, timeout, last_modified) VALUES ('%s', '%s', %d, %d)", $this->dbh->escapeString($key), $this->dbh->escapeString($data), time() + $this->getLifetime($lifetime), time()));
        }

        return (bool) $this->dbh->query(sprintf("INSERT OR REPLACE INTO cache (key, data, timeout, last_modified) VALUES ('%s', '%s', %d, %d)", sqlite_escape_string($key), sqlite_escape_string($data), time() + $this->getLifetime($lifetime), time()));
    }

    /**
     * @see \sfCache
     */
    public function remove($key)
    {
        if ($this->isSqLite3()) {
            return $this->dbh->exec(sprintf("DELETE FROM cache WHERE key = '%s'", $this->dbh->escapeString($key)));
        }

        return (bool) $this->dbh->query(sprintf("DELETE FROM cache WHERE key = '%s'", sqlite_escape_string($key)));
    }

    /**
     * @see \sfCache
     */
    public function removePattern($pattern)
    {
        if ($this->isSqLite3()) {
            return $this->dbh->exec(sprintf("DELETE FROM cache WHERE REGEXP('%s', key)", $this->dbh->escapeString(self::patternToRegexp($pattern))));
        }

        return (bool) $this->dbh->query(sprintf("DELETE FROM cache WHERE REGEXP('%s', key)", sqlite_escape_string(self::patternToRegexp($pattern))));
    }

    /**
     * @see \sfCache
     */
    public function clean($mode = \sfCache::ALL)
    {
        if ($this->isSqLite3()) {
            $res = $this->dbh->exec('DELETE FROM cache'.(\sfCache::OLD == $mode ? sprintf(" WHERE timeout < '%s'", time()) : ''));

            if ($res) {
                return (bool) $this->dbh->changes();
            }

            return false;
        }

        return (bool) $this->dbh->query('DELETE FROM cache'.(\sfCache::OLD == $mode ? sprintf(" WHERE timeout < '%s'", time()) : ''))->numRows();
    }

    /**
     * @see \sfCache
     */
    public function getTimeout($key)
    {
        if ($this->isSqLite3()) {
            $rs = $this->dbh->querySingle(sprintf("SELECT timeout FROM cache WHERE key = '%s' AND timeout > %d", $this->dbh->escapeString($key), time()));

            return null === $rs ? 0 : $rs;
        }

        $rs = $this->dbh->query(sprintf("SELECT timeout FROM cache WHERE key = '%s' AND timeout > %d", sqlite_escape_string($key), time()));

        return $rs->numRows() ? (int) $rs->fetchSingle() : 0;
    }

    /**
     * @see \sfCache
     */
    public function getLastModified($key)
    {
        if ($this->isSqLite3()) {
            $rs = $this->dbh->querySingle(sprintf("SELECT last_modified FROM cache WHERE key = '%s' AND timeout > %d", $this->dbh->escapeString($key), time()));

            return null === $rs ? 0 : $rs;
        }

        $rs = $this->dbh->query(sprintf("SELECT last_modified FROM cache WHERE key = '%s' AND timeout > %d", sqlite_escape_string($key), time()));

        return $rs->numRows() ? (int) $rs->fetchSingle() : 0;
    }

    /**
     * Callback used when deleting keys from cache.
     *
     * @param string $regexp
     * @param string $key
     *
     * @return int
     */
    public function removePatternRegexpCallback($regexp, $key)
    {
        return preg_match($regexp, $key);
    }

    /**
     * @see \sfCache
     */
    public function getMany($keys)
    {
        if ($this->isSqLite3()) {
            $data = [];
            if ($results = $this->dbh->query(sprintf("SELECT key, data FROM cache WHERE key IN ('%s') AND timeout > %d", implode('\', \'', array_map([$this->dbh, 'escapeString'], $keys)), time()))) {
                while ($row = $results->fetchArray()) {
                    $data[$row['key']] = $row['data'];
                }
            }

            return $data;
        }

        $rows = $this->dbh->arrayQuery(sprintf("SELECT key, data FROM cache WHERE key IN ('%s') AND timeout > %d", implode('\', \'', array_map('sqlite_escape_string', $keys)), time()));

        $data = [];
        foreach ($rows as $row) {
            $data[$row['key']] = $row['data'];
        }

        return $data;
    }

    /**
     * Sets the database name.
     *
     * @param string $database The database name where to store the cache
     *
     * @throws \sfCacheException
     */
    protected function setDatabase($database)
    {
        $this->database = $database;

        $new = false;
        if (':memory:' == $database) {
            $new = true;
        } elseif (!is_file($database)) {
            $new = true;

            // create cache dir if needed
            $dir = dirname($database);
            $current_umask = umask(0000);
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }

            touch($database);
            umask($current_umask);
        }

        if ($this->isSqLite3()) {
            $this->dbh = new \SQLite3($this->database);
            if ('not an error' !== $errmsg = $this->dbh->lastErrorMsg()) {
                throw new \sfCacheException(sprintf('Unable to connect to SQLite database: %s.', $errmsg));
            }
        } else {
            if (!$this->dbh = new \SQLiteDatabase($this->database, 0644, $errmsg)) {
                throw new \sfCacheException(sprintf('Unable to connect to SQLite database: %s.', $errmsg));
            }
        }

        $this->dbh->createFunction('regexp', [$this, 'removePatternRegexpCallback'], 2);

        if ($new) {
            $this->createSchema();
        }
    }

    /**
     * Creates the database schema.
     *
     * @throws \sfCacheException
     */
    protected function createSchema()
    {
        $statements = [
            'CREATE TABLE [cache] (
        [key] VARCHAR(255),
        [data] LONGVARCHAR,
        [timeout] TIMESTAMP,
        [last_modified] TIMESTAMP
      )',
            'CREATE UNIQUE INDEX [cache_unique] ON [cache] ([key])',
        ];

        foreach ($statements as $statement) {
            if (false === $this->dbh->query($statement)) {
                $message = $this->isSqLite3() ? $this->dbh->lastErrorMsg() : sqlite_error_string($this->dbh->lastError());

                throw new \sfCacheException($message);
            }
        }
    }

    /**
     * Checks if sqlite is version 3.
     *
     * @return bool
     */
    protected function isSqLite3()
    {
        return 3 === $this->getSqLiteVersion();
    }

    /**
     * Get sqlite version number.
     *
     * @return int
     */
    protected function getSqLiteVersion()
    {
        if (null === $this->sqlLiteVersion) {
            $this->sqlLiteVersion = version_compare(PHP_VERSION, '5.3', '>') ? 3 : 2;
        }

        return $this->sqlLiteVersion;
    }
}
