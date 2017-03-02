<?php
/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPsrLoggerAdapter is meant to be able to use a Prs compliant logger in symfony 1.
 *
 * @see https://github.com/php-fig/log
 *
 * @package    symfony
 * @subpackage service
 * @author     Martin Poirier Theoret <mpoiriert@gmail.com>
 */
class sfPsrLoggerAdapter extends sfLogger
{
  /**
   * Buffer to keep all the log before the psr logger is registered
   *
   * @var array
   */
  private $buffer = array();

  /**
   * The logger that will the log will be forward to
   *
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * The service id that will be use as the psr logger
   *
   * @var string
   */
  private $loggerServiceId = 'logger.psr';

  /**
   * Initializes this logger.
   *
   * Available options:
   *
   * - logger_service_id: The service id to use as the logger. Default: logger.psr
   * - auto_connect: If we must connect automatically to the context.load_factories to set the logger. Default: true
   *
   * @param sfEventDispatcher $dispatcher
   * @param array $options
   *
   * @return void
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    if (isset($options['logger_service_id']))
    {
      $this->loggerServiceId = $options['logger_service_id'];
    }

    if (!isset($options['auto_connect']) || $options['auto_connect'])
    {
      $dispatcher->connect('context.load_factories', array($this, 'listenContextLoadFactoriesEvent'));
    }

    parent::initialize($dispatcher, $options);
  }

  /**
   * Listen the context load factories to get the configure service after the service container is available
   *
   * @param sfEvent $event
   *
   * @return void
   */
  public function listenContextLoadFactoriesEvent(sfEvent $event)
  {
    $context = $event->getSubject();
    /* @var $context sfContext */
    $this->setLogger($context->getService($this->loggerServiceId));
    $this->dispatcher->disconnect('context.load_factories', array($this, 'listenContextLoadFactoriesEvent'));
  }

  /**
   * Set the logger
   *
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function setLogger(\Psr\Log\LoggerInterface $logger)
  {
    $this->logger = $logger;
    $this->flushBuffer();
  }

  /**
   * Flush the current buffer to the register logger
   *
   * @return void
   */
  public function flushBuffer()
  {
    if (!$this->logger)
    {
      $this->buffer = array();
      return;
    }

    foreach ($this->buffer as $log)
    {
      $this->log($log['message'], $log['priority']);
    }

    $this->buffer = array();
  }

  /**
   * Logs a message.
   *
   * @param string $message Message
   * @param int    $priority Message priority
   *
   * @return void
   */
  public function doLog($message, $priority)
  {
    if (!$this->logger)
    {
      $this->buffer[] = compact('message', 'priority');
      return;
    }

    switch ($priority)
    {
      case sfLogger::EMERG:
        $this->logger->emergency($message);
        break;
      case sfLogger::ALERT:
        $this->logger->alert($message);
        break;
      case sfLogger::CRIT:
        $this->logger->critical($message);
        break;
      case sfLogger::ERR:
        $this->logger->error($message);
        break;
      case sfLogger::WARNING:
        $this->logger->warning($message);
        break;
      case sfLogger::NOTICE:
        $this->logger->notice($message);
        break;
      case sfLogger::INFO:
        $this->logger->info($message);
        break;
      case sfLogger::DEBUG:
        $this->logger->debug($message);
        break;
    }
  }
}
