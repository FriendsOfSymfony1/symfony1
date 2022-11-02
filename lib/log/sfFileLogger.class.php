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
    $fp         = null,
    $formatMap  = array(
      // abbreviated weekday name
      '%a' => 'D',
      // full weekday name
      '%A' => 'l',
      // abbreviated month name
      '%b' => 'M',
      // full month name
      '%B' => 'F',
      // preferred date and time representation
      // FIXME: How to get the preferred intl format?
      '%c' => 'Y-m-d H:i:s',
      // century number (the year divided by 100, range 00 to 99)
      // FIXME: format() does not support century.
      '%C' => '',
      // day of the month (01 to 31)
      '%d' => 'd',
      // same as %m/%d/%y
      '%D' => 'm/d/y',
      // day of the month (1 to 31)
      '%e' => 'j',
      // like %G, but without the century
      // FIXME: format() does not support the ISO week number without century.
      '%g' => '',
      // 4-digit year corresponding to the ISO week number (see %V).
      '%G' => 'o',
      // same as %b
      '%h' => 'M',
      // hour, using a 24-hour clock (00 to 23)
      '%H' => 'H',
      // hour, using a 12-hour clock (01 to 12)
      '%I' => 'h',
      // day of the year (001 to 366)
      // FIXME: format() starts at 0
      '%j' => 'z',
      // month (01 to 12)
      '%m' => 'm',
      // minute
      '%M' => 'i',
      // newline character
      '%n' => PHP_EOL,
      // either am or pm according to the given time value
      '%p' => 'a',
      // time in a.m. and p.m. notation
      '%r' => 'h:i:s A',
      // time in 24 hour notation
      '%R' => 'H:i:s',
      // second
      '%S' => 's',
      // tab character
      '%t' => "\t",
      // current time, equal to %H:%M:%S
      '%T' => '%H:i:s',
      // weekday as a number (1 to 7), Monday=1. Warning: In Sun Solaris Sunday=1
      '%u' => 'N',
      // week number of the current year, starting with the first Sunday as the first day of the first week
      // FIXME: format() does not support the week number starting on sunday.
      '%U' => '',
      // The ISO 8601 week number of the current year (01 to 53), where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week
      // FIXME: same as %W?
      '%V' => 'W',
      // week number of the current year, starting with the first Monday as the first day of the first week
      '%W' => 'W',
      // day of the week as a decimal, Sunday=0
      '%w' => 'w',
      // preferred date representation without the time
      '%x' => 'Y-m-d',
      // preferred time representation without the date
      '%X' => 'H:i:s',
      // year without a century (range 00 to 99)
      '%y' => 'y',
      // year including the century
      '%Y' => 'Y',
      // time zone or name or abbreviation
      '%Z' => 'Z',
      '%z' => 'Z',
      // a literal % character
      '%%' => '%',
    );

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
   * @param int $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    flock($this->fp, LOCK_EX);
    fwrite($this->fp, strtr($this->format, array(
      '%type%'     => $this->type,
      '%message%'  => $message,
      '%time%'     => self::strftime($this->timeFormat),
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

  /**
   * @param $format
   * @return false|string
   */
  public static function strftime($format)
  {
    if (version_compare(PHP_VERSION, '8.1.0') < 0)
    {
      return strftime($format);
    }
    return date(self::_strftimeFormatToDateFormat($format));
  }

  /**
   * Try to Convert a strftime to date format
   *
   * Unable to find a perfect implementation, based on those one (Each contains some errors)
   * https://github.com/Fabrik/fabrik/blob/master/plugins/fabrik_element/date/date.php
   * https://gist.github.com/mcaskill/02636e5970be1bb22270
   * https://stackoverflow.com/questions/22665959/using-php-strftime-using-date-format-string
   *
   * Limitation:
   * - Do not apply translation
   * - Some few strftime format could be broken (low probability to be used on logs)
   *
   * Private: because it should not be used outside of this scope
   *
   * A better solution is to use : IntlDateFormatter, but it will require to load a new php extension, which could break some setup.
   *
   * @return array|string|string[]
   */
  private static function _strftimeFormatToDateFormat($strftimeFormat)
  {
    // Missing %V %C %g %G
    $search = array(
      '%a', '%A', '%d', '%e', '%u',
      '%w', '%W', '%b', '%h', '%B',
      '%m', '%y', '%Y', '%D', '%F',
      '%x', '%n', '%t', '%H', '%k',
      '%I', '%l', '%M', '%p', '%P',
      '%r' /* %I:%M:%S %p */, '%R' /* %H:%M */, '%S', '%T' /* %H:%M:%S */, '%X', '%z', '%Z',
      '%c', '%s', '%j',
      '%%');

    $replace = array(
      'D', 'l', 'd', 'j', 'N',
      'w', 'W', 'M', 'M', 'F',
      'm', 'y', 'Y', 'm/d/y', 'Y-m-d',
      'm/d/y', "\n", "\t", 'H', 'G',
      'h', 'g', 'i', 'A', 'a',
      'h:i:s A', 'H:i', 's', 'H:i:s', 'H:i:s', 'O', 'T',
      'D M j H:i:s Y' /*Tue Feb 5 00:45:10 2009*/, 'U', 'z',
      '%');

    return str_replace($search, $replace, $strftimeFormat);
  }
}
