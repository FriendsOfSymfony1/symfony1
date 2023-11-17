<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLogger is the abstract class for all logging classes.
 *
 * This level list is ordered by highest priority (self::EMERG) to lowest priority (self::DEBUG):
 * - EMERG:   System is unusable
 * - ALERT:   Immediate action required
 * - CRIT:    Critical conditions
 * - ERR:     Error conditions
 * - WARNING: Warning conditions
 * - NOTICE:  Normal but significant
 * - INFO:    Informational
 * - DEBUG:   Debug-level messages
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfLogger implements sfLoggerInterface
{
    public const EMERG = 0; // System is unusable
    public const ALERT = 1; // Immediate action required
    public const CRIT = 2; // Critical conditions
    public const ERR = 3; // Error conditions
    public const WARNING = 4; // Warning conditions
    public const NOTICE = 5; // Normal but significant
    public const INFO = 6; // Informational
    public const DEBUG = 7; // Debug-level messages

    /** @var sfEventDispatcher */
    protected $dispatcher;

    /** @var array */
    protected $options = array();

    /** @var int */
    protected $level = self::INFO;

    /**
     * Class constructor.
     *
     * @see initialize()
     *
     * @param sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array             $options    an array of options
     */
    public function __construct(sfEventDispatcher $dispatcher, $options = array())
    {
        $this->initialize($dispatcher, $options);

        if (!isset($options['auto_shutdown']) || $options['auto_shutdown']) {
            register_shutdown_function(array($this, 'shutdown'));
        }
    }

    /**
     * Initializes this sfLogger instance.
     *
     * Available options:
     *
     * - level: The log level.
     *
     * @param sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array             $options    an array of options
     *
     * @throws <b>sfInitializationException</b> If an error occurs while initializing this sfLogger
     */
    public function initialize(sfEventDispatcher $dispatcher, $options = array())
    {
        $this->dispatcher = $dispatcher;
        $this->options = $options;

        if (isset($this->options['level'])) {
            $this->setLogLevel($this->options['level']);
        }

        $dispatcher->connect('application.log', array($this, 'listenToLogEvent'));
    }

    /**
     * Returns the options for the logger instance.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns the options for the logger instance.
     *
     * @param string $name
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Retrieves the log level for the current logger instance.
     *
     * @return int Log level
     */
    public function getLogLevel()
    {
        return $this->level;
    }

    /**
     * Sets a log level for the current logger instance.
     *
     * @param int $level Log level
     */
    public function setLogLevel($level)
    {
        if (!is_int($level)) {
            $level = constant('sfLogger::'.strtoupper($level));
        }

        $this->level = $level;
    }

    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     *
     * @return bool|void
     */
    public function log($message, $priority = self::INFO)
    {
        if ($this->getLogLevel() < $priority) {
            return false;
        }

        $this->doLog($message, $priority);
    }

    /**
     * Logs an emerg message.
     *
     * @param string $message Message
     */
    public function emerg($message)
    {
        $this->log($message, self::EMERG);
    }

    /**
     * Logs an alert message.
     *
     * @param string $message Message
     */
    public function alert($message)
    {
        $this->log($message, self::ALERT);
    }

    /**
     * Logs a critical message.
     *
     * @param string $message Message
     */
    public function crit($message)
    {
        $this->log($message, self::CRIT);
    }

    /**
     * Logs an error message.
     *
     * @param string $message Message
     */
    public function err($message)
    {
        $this->log($message, self::ERR);
    }

    /**
     * Logs a warning message.
     *
     * @param string $message Message
     */
    public function warning($message)
    {
        $this->log($message, self::WARNING);
    }

    /**
     * Logs a notice message.
     *
     * @param string $message Message
     */
    public function notice($message)
    {
        $this->log($message, self::NOTICE);
    }

    /**
     * Logs an info message.
     *
     * @param string $message Message
     */
    public function info($message)
    {
        $this->log($message, self::INFO);
    }

    /**
     * Logs a debug message.
     *
     * @param string $message Message
     */
    public function debug($message)
    {
        $this->log($message, self::DEBUG);
    }

    /**
     * Listens to application.log events.
     *
     * @param sfEvent $event An sfEvent instance
     */
    public function listenToLogEvent(sfEvent $event)
    {
        $priority = isset($event['priority']) ? $event['priority'] : self::INFO;

        $subject = $event->getSubject();
        $subject = is_object($subject) ? get_class($subject) : (is_string($subject) ? $subject : 'main');
        foreach ($event->getParameters() as $key => $message) {
            if ('priority' === $key) {
                continue;
            }

            $this->log(sprintf('{%s} %s', $subject, $message), $priority);
        }
    }

    /**
     * Executes the shutdown procedure.
     *
     * Cleans up the current logger instance.
     */
    public function shutdown() {}

    /**
     * Returns the priority name given a priority class constant.
     *
     * @param int $priority A priority class constant
     *
     * @return string The priority name
     *
     * @throws sfException if the priority level does not exist
     */
    public static function getPriorityName($priority)
    {
        static $levels = array(
            self::EMERG => 'emerg',
            self::ALERT => 'alert',
            self::CRIT => 'crit',
            self::ERR => 'err',
            self::WARNING => 'warning',
            self::NOTICE => 'notice',
            self::INFO => 'info',
            self::DEBUG => 'debug',
        );

        if (!isset($levels[$priority])) {
            throw new sfException(sprintf('The priority level "%s" does not exist.', $priority));
        }

        return $levels[$priority];
    }

    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     */
    abstract protected function doLog($message, $priority);
}
