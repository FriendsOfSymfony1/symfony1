<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache class that stores cached content in APC.
 *
 * @package    symfony
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfAPCCache extends sfCache
{
  protected $enabled;

  /**
   * Initializes this sfCache instance.
   *
   * Available options:
   *
   * * see sfCache for options available for all drivers
   *
   * @see sfCache
   * @inheritdoc
   */
  public function initialize($options = array())
  {
    parent::initialize($options);

    $this->enabled = function_exists('apc_store') && ini_get('apc.enabled');
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function get($key, $default = null)
  {
    if (!$this->enabled)
    {
      return $default;
    }

    $value = $this->fetch($this->getOption('prefix').$key, $has);

    return $has ? $value : $default;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function has($key)
  {
    if (!$this->enabled)
    {
      return false;
    }

    $this->fetch($this->getOption('prefix').$key, $has);

    return $has;
  }

  private function fetch($key, &$success)
  {
    $has = null;
    $value = apc_fetch($key, $has);
    // the second argument was added in APC 3.0.17. If it is still null we fall back to the value returned
    if (null !== $has)
    {
      $success = $has;
    }
    else
    {
      $success = $value !== false;
    }

    return $value;
  }


  /**
   * @see sfCache
   * @inheritdoc
   */
  public function set($key, $data, $lifetime = null)
  {
    if (!$this->enabled)
    {
      return true;
    }

    return apc_store($this->getOption('prefix').$key, $data, $this->getLifetime($lifetime));
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function remove($key)
  {
    if (!$this->enabled)
    {
      return true;
    }

    return apc_delete($this->getOption('prefix').$key);
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function clean($mode = sfCache::ALL)
  {
    if (!$this->enabled)
    {
      return true;
    }

    if (sfCache::ALL === $mode)
    {
      return apc_clear_cache('user');
    }
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getLastModified($key)
  {
    if ($info = $this->getCacheInfo($key))
    {
      return $info['creation_time'] + $info['ttl'] > time() ? $info['mtime'] : 0;
    }

    return 0;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getTimeout($key)
  {
    if ($info = $this->getCacheInfo($key))
    {
      return $info['creation_time'] + $info['ttl'] > time() ? $info['creation_time'] + $info['ttl'] : 0;
    }

    return 0;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function removePattern($pattern)
  {
    if (!$this->enabled)
    {
      return true;
    }

    $infos = apc_cache_info('user');
    if (!is_array($infos['cache_list']))
    {
      return;
    }

    $regexp = self::patternToRegexp($this->getOption('prefix').$pattern);

    foreach ($infos['cache_list'] as $info)
    {
      if (preg_match($regexp, $info['info']))
      {
        apc_delete($info['info']);
      }
    }
  }

  /**
   * Gets the cache info
   *
   * @param  string $key The cache key
   *
   * @return string
   */
  protected function getCacheInfo($key)
  {
    if (!$this->enabled)
    {
      return false;
    }

    $infos = apc_cache_info('user');

    if (is_array($infos['cache_list']))
    {
      foreach ($infos['cache_list'] as $info)
      {
        if ($this->getOption('prefix').$key == $info['info'])
        {
          return $info;
        }
      }
    }

    return null;
  }
}
