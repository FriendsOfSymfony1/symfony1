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
 * sfAggregateLogger logs messages through several loggers.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfAggregateLogger extends \sfLogger
{
    protected $loggers = [];

    /**
     * Initializes this logger.
     *
     * Available options:
     *
     * - loggers: Logger objects that extends sfLogger.
     *
     * @param \sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array              $options    an array of options
     */
    public function initialize(\sfEventDispatcher $dispatcher, $options = [])
    {
        $this->dispatcher = $dispatcher;

        if (isset($options['loggers'])) {
            if (!is_array($options['loggers'])) {
                $options['loggers'] = [$options['loggers']];
            }

            $this->addLoggers($options['loggers']);
        }

        parent::initialize($dispatcher, $options);
    }

    /**
     * Retrieves current loggers.
     *
     * @return array List of loggers
     */
    public function getLoggers()
    {
        return $this->loggers;
    }

    /**
     * Adds an array of loggers.
     *
     * @param object $loggers An array of Logger objects
     */
    public function addLoggers($loggers)
    {
        foreach ($loggers as $logger) {
            $this->addLogger($logger);
        }
    }

    /**
     * Adds a logger.
     *
     * @param \sfLoggerInterface $logger The Logger object
     */
    public function addLogger(\sfLoggerInterface $logger)
    {
        $this->loggers[] = $logger;

        $this->dispatcher->disconnect('application.log', [$logger, 'listenToLogEvent']);
    }

    /**
     * Executes the shutdown method.
     */
    public function shutdown()
    {
        foreach ($this->loggers as $logger) {
            if ($logger instanceof \sfLogger) {
                $logger->shutdown();
            }
        }

        $this->loggers = [];
    }

    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     */
    protected function doLog($message, $priority)
    {
        foreach ($this->loggers as $logger) {
            $logger->log($message, $priority);
        }
    }
}
