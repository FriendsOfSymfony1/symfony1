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
 * sfTimerManager is a container for sfTimer objects.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTimerManager
{
    /** @var sfTimer[] */
    public static $timers = [];

    /**
     * Gets a sfTimer instance.
     *
     * It returns the timer named $name or create a new one if it does not exist.
     *
     * @param string $name  The name of the timer
     * @param bool   $reset
     *
     * @return \sfTimer The timer instance
     */
    public static function getTimer($name, $reset = true)
    {
        if (!isset(self::$timers[$name])) {
            self::$timers[$name] = new \sfTimer($name);
        }

        if ($reset) {
            self::$timers[$name]->startTimer();
        }

        return self::$timers[$name];
    }

    /**
     * Gets all sfTimer instances stored in sfTimerManager.
     *
     * @return sfTimer[] An array of all sfTimer instances
     */
    public static function getTimers()
    {
        return self::$timers;
    }

    /**
     * Clears all sfTimer instances stored in sfTimerManager.
     */
    public static function clearTimers()
    {
        self::$timers = [];
    }
}
