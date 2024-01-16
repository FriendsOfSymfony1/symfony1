<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache class that does nothing.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfNoCache extends sfCache
{
    /**
     * @see sfCache
     *
     * @param mixed|null $default
     * @param mixed      $key
     */
    public function get($key, $default = null)
    {
        return $default;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function has($key)
    {
        return false;
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
        return true;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function remove($key)
    {
        return true;
    }

    /**
     * @see sfCache
     *
     * @param mixed $pattern
     */
    public function removePattern($pattern)
    {
        return true;
    }

    /**
     * @see sfCache
     *
     * @param mixed $mode
     */
    public function clean($mode = self::ALL)
    {
        return true;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function getLastModified($key)
    {
        return 0;
    }

    /**
     * @see sfCache
     *
     * @param mixed $key
     */
    public function getTimeout($key)
    {
        return 0;
    }
}
