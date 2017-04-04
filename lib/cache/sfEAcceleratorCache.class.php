<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache class that stores cached content in EAccelerator.
 *
 * @package    symfony
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfEAcceleratorCache extends sfCache
{
  /**
   * Initializes this sfCache instance.
   *
   * Available options:
   *
   * * see sfCache for options available for all drivers
   *
   * @see sfCache
   *
   * @param  array $options
   *
   * @throws sfInitializationException
   */
  public function initialize($options = array())
  {
    parent::initialize($options);

    if (!function_exists('eaccelerator_put') || !ini_get('eaccelerator.enable'))
    {
      throw new sfInitializationException('You must have EAccelerator installed and enabled to use sfEAcceleratorCache class (or perhaps you forgot to add --with-eaccelerator-shared-memory when installing).');
    }
  }

  /**
   * @see sfCache
   *
   * @param string $key
   * @param mixed  $default
   *
   * @return null|string
   */
  public function get($key, $default = null)
  {
    $value = eaccelerator_get($this->getOption('prefix').$key);

    return null === $value ? $default : $value;
  }

  /**
   * @see sfCache
   *
   * @param string $key
   *
   * @return bool
   */
  public function has($key)
  {
    return null !== eaccelerator_get($this->getOption('prefix'.$key));
  }

  /**
   * @see sfCache
   *
   * @param  string   $key
   * @param  string   $data
   * @param  int|null $lifetime
   *
   * @return bool
   */
  public function set($key, $data, $lifetime = null)
  {
    return eaccelerator_put($this->getOption('prefix').$key, $data, $this->getLifetime($lifetime));
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function remove($key)
  {
    return eaccelerator_rm($this->getOption('prefix').$key);
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function removePattern($pattern)
  {
    $infos = eaccelerator_list_keys();

    if (is_array($infos))
    {
      $regexp = self::patternToRegexp($this->getOption('prefix').$pattern);

      foreach ($infos as $info)
      {
        if (preg_match($regexp, $info['name']))
        {
          eaccelerator_rm($this->getOption('prefix').$key);
        }
      }
    }
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function clean($mode = sfCache::ALL)
  {
    if (sfCache::OLD === $mode)
    {
      return eaccelerator_gc();
    }

    $infos = eaccelerator_list_keys();
    if (is_array($infos))
    {
      foreach ($infos as $info)
      {
        if (false !== strpos($info['name'], $this->getOption('prefix')))
        {
          // eaccelerator bug (http://eaccelerator.net/ticket/287)
          $key = 0 === strpos($info['name'], ':') ? substr($info['name'], 1) : $info['name'];
          if (!eaccelerator_rm($key))
          {
            return false;
          }
        }
      }
    }

    return true;
  }

  /**
   * @see sfCache
   * @inheritdoc
   */
  public function getLastModified($key)
  {
    if ($info = $this->getCacheInfo($key))
    {
      return $info['created'];
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
      return -1 == $info['ttl'] ? 0 : $info['created'] + $info['ttl'];
    }

    return 0;
  }

  protected function getCacheInfo($key)
  {
    $infos = eaccelerator_list_keys();

    if (is_array($infos))
    {
      foreach ($infos as $info)
      {
        if ($this->getOption('prefix').$key == $info['name'])
        {
          return $info;
        }
      }
    }

    return null;
  }
}
