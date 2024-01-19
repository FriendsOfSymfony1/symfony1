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
 * sfTimer class allows to time some PHP code.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTimer
{
    protected $startTime;
    protected $totalTime;
    protected $name = '';
    protected $calls = 0;

    /**
     * Creates a new sfTimer instance.
     *
     * @param string $name The name of the timer
     */
    public function __construct($name = '')
    {
        $this->name = $name;
        $this->startTimer();
    }

    /**
     * Starts the timer.
     */
    public function startTimer()
    {
        $this->startTime = microtime(true);
    }

    /**
     * Stops the timer and add the amount of time since the start to the total time.
     *
     * @return float Time spend for the last call
     */
    public function addTime()
    {
        $spend = microtime(true) - $this->startTime;
        $this->totalTime += $spend;
        ++$this->calls;

        return $spend;
    }

    /**
     * Gets the number of calls this timer has been called to time code.
     *
     * @return int Number of calls
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Gets the total time elapsed for all calls of this timer.
     *
     * @return float Time in seconds
     */
    public function getElapsedTime()
    {
        if (null === $this->totalTime) {
            $this->addTime();
        }

        return $this->totalTime;
    }
}
