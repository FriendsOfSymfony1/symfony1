<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfAggregateLogger logs messages through several loggers.
 *
 * @package    symfony
 * @subpackage log
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfAggregateLogger extends sfLogger
{
  protected
    $loggers = array();

  /**
   * Initializes this logger.
   *
   * Available options:
   *
   * - loggers: Logger objects that extends sfLogger.
   *
   * @param  sfEventDispatcher $dispatcher  A sfEventDispatcher instance
   * @param  array             $options     An array of options.
   *
   * @return void
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    $this->dispatcher = $dispatcher;

    if (isset($options['loggers']))
    {
      if (!is_array($options['loggers']))
      {
        $options['loggers'] = array($options['loggers']);
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
    foreach ($loggers as $logger)
    {
      $this->addLogger($logger);
    }
  }

  /**
   * Adds a logger.
   *
   * @param sfLoggerInterface $logger The Logger object
   */
  public function addLogger(sfLoggerInterface $logger)
  {
    $this->loggers[] = $logger;

    $this->dispatcher->disconnect('application.log', array($logger, 'listenToLogEvent'));
  }

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param int    $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    foreach ($this->loggers as $logger)
    {
      $logger->log($message, $priority);
    }
  }

  /**
   * Executes the shutdown method.
   */
  public function shutdown()
  {
    foreach ($this->loggers as $logger)
    {
      if ($logger instanceof sfLogger)
      {
        $logger->shutdown();
      }
    }

    $this->loggers = array();
  }
}
