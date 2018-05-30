<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfFileLogger logs messages in a file.
 *
 * @package    symfony
 * @subpackage log
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfFileLogger extends sfLogger
{
  protected
    $type       = 'symfony',
    $format     = '%time% %type% [%priority%] %message%%EOL%',
    $timeFormat = '%b %d %H:%M:%S',
    $fp         = null;

  /**
   * Initializes this logger.
   *
   * Available options:
   *
   * - file:        The file path or a php wrapper to log messages
   *                You can use any support php wrapper. To write logs to the Apache error log, use php://stderr
   * - format:      The log line format (default to %time% %type% [%priority%] %message%%EOL%)
   * - time_format: The log time strftime format (default to %b %d %H:%M:%S)
   * - dir_mode:    The mode to use when creating a directory (default to 0777)
   * - file_mode:   The mode to use when creating a file (default to 0666)
   *
   * @param  sfEventDispatcher $dispatcher  A sfEventDispatcher instance
   * @param  array             $options     An array of options.
   *
   * @return void
   *
   * @throws sfConfigurationException
   * @throws sfFileException
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    if (!isset($options['file']))
    {
      throw new sfConfigurationException('You must provide a "file" parameter for this logger.');
    }

    if (isset($options['format']))
    {
      $this->format = $options['format'];
    }

    if (isset($options['time_format']))
    {
      $this->timeFormat = $options['time_format'];
    }

    if (isset($options['type']))
    {
      $this->type = $options['type'];
    }

    $dir     = dirname($options['file']);
    $dirMode = isset($options['dir_mode']) ? $options['dir_mode'] : 0777;
    if (!is_dir($dir) && !@mkdir($dir, $dirMode, true) && !is_dir($dir))
    {
      throw new \RuntimeException(sprintf('Logger was not able to create a directory "%s"', $dir));
    }

    $fileExists = file_exists($options['file']);
    if (!is_writable($dir) || ($fileExists && !is_writable($options['file'])))
    {
      throw new sfFileException(sprintf('Unable to open the log file "%s" for writing.', $options['file']));
    }

    $this->fp = fopen($options['file'], 'a');
    if (!$fileExists)
    {
      chmod($options['file'], isset($options['file_mode']) ? $options['file_mode'] : 0666);
    }

    parent::initialize($dispatcher, $options);
  }

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param int    $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    flock($this->fp, LOCK_EX);
    fwrite($this->fp, strtr($this->format, array(
      '%type%'     => $this->type,
      '%message%'  => $message,
      '%time%'     => strftime($this->timeFormat),
      '%priority%' => $this->getPriority($priority),
      '%EOL%'      => PHP_EOL,
    )));
    flock($this->fp, LOCK_UN);
  }

  /**
   * Returns the priority string to use in log messages.
   *
   * @param  string $priority The priority constant
   *
   * @return string The priority to use in log messages
   */
  protected function getPriority($priority)
  {
    return sfLogger::getPriorityName($priority);
  }

  /**
   * Executes the shutdown method.
   */
  public function shutdown()
  {
    if (is_resource($this->fp))
    {
      fclose($this->fp);
    }
  }
}
