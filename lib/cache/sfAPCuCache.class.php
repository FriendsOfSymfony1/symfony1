<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache class that stores cached content in APCu.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Paulo M
 *
 * @version    SVN: $Id$
 */
class sfAPCuCache extends sfCache
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
     *
     * @param mixed $options
     */
    public function initialize($options = [])
    {
        parent::initialize($options);

        $this->enabled = function_exists('apcu_store') && ini_get('apc.enabled');
    }

    /**
     * @see sfCache
     *
     * @param mixed|null $default
     * @param mixed      $key
     */
    public function get($key, $default = null)
    {
        if (!$this->enabled) {
            return $default;
        }

        $value = $this->fetch($this->getOption('prefix').$key, $has);

        return $has ? $value : $default;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function has($key)
    {
        if (!$this->enabled) {
            return false;
        }

        $this->fetch($this->getOption('prefix').$key, $has);

        return $has;
    }

    /**
     * @see sfCache
     *
     * @param mixed|null $lifetime
     * @param mixed      $key
     * @param mixed      $data
     */
    public function set($key, $data, $lifetime = null)
    {
        if (!$this->enabled) {
            return true;
        }

        return apcu_store($this->getOption('prefix').$key, $data, $this->getLifetime($lifetime));
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function remove($key)
    {
        if (!$this->enabled) {
            return true;
        }

        return apcu_delete($this->getOption('prefix').$key);
    }

    /**
     * @see sfCache
     *
     * @param mixed $mode
     */
    public function clean($mode = sfCache::ALL)
    {
        if (!$this->enabled) {
            return true;
        }

        if (sfCache::ALL === $mode) {
            return apcu_clear_cache();
        }
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function getLastModified($key)
    {
        if ($info = $this->getCacheInfo($key)) {
            return $info['creation_time'] + $info['ttl'] > time() ? $info['mtime'] : 0;
        }

        return 0;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function getTimeout($key)
    {
        if ($info = $this->getCacheInfo($key)) {
            return $info['creation_time'] + $info['ttl'] > time() ? $info['creation_time'] + $info['ttl'] : 0;
        }

        return 0;
    }

    /**
     * @see sfCache
     *
     * @param mixed $pattern
     */
    public function removePattern($pattern)
    {
        if (!$this->enabled) {
            return true;
        }

        $infos = apcu_cache_info();
        if (!is_array($infos['cache_list'])) {
            return;
        }

        $regexp = self::patternToRegexp($this->getOption('prefix').$pattern);

        foreach ($infos['cache_list'] as $info) {
            if (preg_match($regexp, $info['info'])) {
                apcu_delete($info['info']);
            }
        }
    }

    /**
     * Gets the cache info.
     *
     * @param string $key The cache key
     *
     * @return string
     */
    protected function getCacheInfo($key)
    {
        if (!$this->enabled) {
            return false;
        }

        $infos = apcu_cache_info();

        if (is_array($infos['cache_list'])) {
            foreach ($infos['cache_list'] as $info) {
                if ($this->getOption('prefix').$key == $info['info']) {
                    return $info;
                }
            }
        }

        return null;
    }

    private function fetch($key, &$success)
    {
        $has = null;
        $value = apcu_fetch($key, $has);
        // the second argument was added in APC 3.0.17. If it is still null we fall back to the value returned
        if (null !== $has) {
            $success = $has;
        } else {
            $success = false !== $value;
        }

        return $value;
    }
}
