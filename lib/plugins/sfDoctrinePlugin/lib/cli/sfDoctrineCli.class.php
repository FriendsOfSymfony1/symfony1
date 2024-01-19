<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineCli.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineCli extends Doctrine_Cli
{
    protected $symfonyDispatcher;
    protected $symfonyFormatter;

    /**
     * Set the symfony dispatcher of the cli instance.
     *
     * @param object $dispatcher
     */
    public function setSymfonyDispatcher($dispatcher)
    {
        $this->symfonyDispatcher = $dispatcher;
    }

    /**
     * Set the symfony formatter to use for the cli.
     *
     * @param object $formatter
     */
    public function setSymfonyFormatter($formatter)
    {
        $this->symfonyFormatter = $formatter;
    }

    /**
     * Notify the dispatcher of a message. We silent the messages from the Doctrine cli.
     *
     * @param string $notification
     * @param string $style
     *
     * @return false
     */
    public function notify($notification = null, $style = 'HEADER')
    {
        $this->symfonyDispatcher->notify(new sfEvent($this, 'command.log', [$this->symfonyFormatter->formatSection('doctrine', $notification)]));
    }

    /**
     * Notify symfony of an exception thrown by the Doctrine cli.
     *
     * @param Doctrine_Exception $exception
     *
     * @throws sfException
     */
    public function notifyException(Exception $exception)
    {
        throw $exception;
    }
}
