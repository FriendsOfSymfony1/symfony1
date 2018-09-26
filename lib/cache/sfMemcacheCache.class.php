<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache class that stores cached content in memcache.
 *
 * @package    symfony
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfMemcacheCache extends sfCache
{
  /** @var Memcache */
  protected $memcached = null;

  /**
   * Initializes this sfCache instance.
   *
   * Available options:
   *
   * * memcache: A memcached object (optional)
   *
   * * host:       The default host (default to localhost)
   * * port:       The port for the default server (default to 11211)
   * * persistent: true if the connection must be persistent, false otherwise (true by default)
   *
   * * servers:    An array of additional servers (keys: host, port, persistent)
   *
   * * see sfCache for options available for all drivers
   *
   * @see sfCache
   * @inheritdoc
   */
  public function initialize($options = array())
  {
    parent::initialize($options);

    if (!class_exists('Memcached'))
    {
      throw new sfInitializationException('You must have memcached installed and enabled to use sfMemcacheCache class.');
    }

    if ($this->getOption('memcached'))
    {
      $this->memcached = $this->getOption('memcached');
    }
    else
    {
      $this->memcached = new Memcached();

      if ($this->getOption('servers'))
      {
        foreach ($this->getOption('servers') as $server)
        {
          $port = isset($server['port']) ? $server['port'] : 11211;
          if (!$this->memcached->addServer($server['host'], $port))
          {
            throw new sfInitializationException(sprintf('Unable to connect to the memcached server (%s:%s).', $server['host'], $port));
          }
        }
      }
      else
      {
        $port = $this->getOption('port', 11211);
        $host = $this->getOption('host', 'localhost');

        if (!$this->memcached->addServer($host, $port))
        {
          throw new sfInitializationException(sprintf('Unable to connect to the memcached server (%s:%s).', $host, $port));
        }
      }
    }
  }

  /**
   * @see sfCache
   * @return Memcache
   */
  public function getBackend()
  {
    return $this->memcached;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function get($key, $default = null)
  {
    $value = $this->memcached->get($this->getOption('prefix').$key);

    return (false === $value && false === $this->getMetadata($key)) ? $default : $value;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function has($key)
  {
    if (false === $this->memcached->get($this->getOption('prefix') . $key))
    {
      // if there is metadata, $key exists with a false value
      return !(false === $this->getMetadata($key));
    }

    return true;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function set($key, $data, $lifetime = null)
  {
    $lifetime = null === $lifetime ? $this->getOption('lifetime') : $lifetime;

    // save metadata
    $this->setMetadata($key, $lifetime);

    // save key for removePattern()
    if ($this->getOption('storeCacheInfo', false))
    {
      $this->setCacheInfo($key);
    }

    if (false !== $this->memcached->replace($this->getOption('prefix').$key, $data, time() + $lifetime))
    {
      return true;
    }

    return $this->memcached->set($this->getOption('prefix').$key, $data, time() + $lifetime);
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function remove($key)
  {
    // delete metadata
    $this->memcached->delete($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key, 0);
    if ($this->getOption('storeCacheInfo', false))
    {
      $this->setCacheInfo($key, true);
    }
    return $this->memcached->delete($this->getOption('prefix').$key, 0);
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function clean($mode = sfCache::ALL)
  {
    if (sfCache::ALL === $mode)
    {
      return $this->memcached->flush();
    }
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getLastModified($key)
  {
    if (false === ($retval = $this->getMetadata($key)))
    {
      return 0;
    }

    return $retval['lastModified'];
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getTimeout($key)
  {
    if (false === ($retval = $this->getMetadata($key)))
    {
      return 0;
    }

    return $retval['timeout'];
  }

  /**
   * @see sfCache
   * @inheritdoc
   *
   * @throws sfCacheException
   */
  public function removePattern($pattern)
  {
    if (!$this->getOption('storeCacheInfo', false))
    {
      throw new sfCacheException('To use the "removePattern" method, you must set the "storeCacheInfo" option to "true".');
    }

    $regexp = self::patternToRegexp($this->getOption('prefix').$pattern);
    foreach ($this->getCacheInfo() as $key)
    {
      if (preg_match($regexp, $key))
      {
        $this->remove(substr($key, strlen($this->getOption('prefix'))));
      }
    }
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getMany($keys)
  {
    $values = array();
    $prefix = $this->getOption('prefix');
    $prefixed_keys = array_map(function($k) use ($prefix) { return $prefix . $k; }, $keys);

    foreach ($this->memcached->get($prefixed_keys) as $key => $value)
    {
      $values[str_replace($prefix, '', $key)] = $value;
    }

    return $values;
  }

  /**
   * Gets metadata about a key in the cache.
   *
   * @param string $key A cache key
   *
   * @return array An array of metadata information
   */
  protected function getMetadata($key)
  {
    return $this->memcached->get($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key);
  }

  /**
   * Stores metadata about a key in the cache.
   *
   * @param string $key      A cache key
   * @param string $lifetime The lifetime
   */
  protected function setMetadata($key, $lifetime)
  {
    $this->memcached->set($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key, array('lastModified' => time(), 'timeout' => time() + $lifetime), time() + $lifetime);
  }

  /**
   * Updates the cache information for the given cache key.
   *
   * @param string $key The cache key
   * @param boolean $delete Delete key or not
   */
  protected function setCacheInfo($key, $delete = false)
  {
    $keys = $this->memcached->get($this->getOption('prefix').'_metadata');
    if (!is_array($keys))
    {
      $keys = array();
    }

    if ($delete)
    {
       if (($k = array_search($this->getOption('prefix').$key, $keys)) !== false)
       {
         unset($keys[$k]);
       }
    }
    else
    {
      if (!in_array($this->getOption('prefix').$key, $keys))
      {
        $keys[] = $this->getOption('prefix').$key;
      }
    }

    $this->memcached->set($this->getOption('prefix').'_metadata', $keys, 0);
  }

  /**
   * Gets cache information.
   *
   * @return array
   */
  protected function getCacheInfo()
  {
    $keys = $this->memcached->get($this->getOption('prefix').'_metadata');
    if (!is_array($keys))
    {
      return array();
    }

    return $keys;
  }
}
