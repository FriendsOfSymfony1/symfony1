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
 * sfFileLogger logs messages in a file.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfFileLogger extends \sfLogger
{
    protected $type = 'symfony';
    protected $format = '%time% %type% [%priority%] %message%%EOL%';
    protected $timeFormat = '%b %d %H:%M:%S';
    protected $fp;

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
     * @param \sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array              $options    an array of options
     *
     * @throws \sfConfigurationException
     * @throws \sfFileException
     */
    public function initialize(\sfEventDispatcher $dispatcher, $options = [])
    {
        if (!isset($options['file'])) {
            throw new \sfConfigurationException('You must provide a "file" parameter for this logger.');
        }

        if (isset($options['format'])) {
            $this->format = $options['format'];
        }

        if (isset($options['time_format'])) {
            $this->timeFormat = $options['time_format'];
        }

        if (isset($options['type'])) {
            $this->type = $options['type'];
        }

        $dir = dirname($options['file']);
        $dirMode = isset($options['dir_mode']) ? $options['dir_mode'] : 0777;
        if (!is_dir($dir) && !@mkdir($dir, $dirMode, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Logger was not able to create a directory "%s"', $dir));
        }

        $fileExists = file_exists($options['file']);
        if (!is_writable($dir) || ($fileExists && !is_writable($options['file']))) {
            throw new \sfFileException(sprintf('Unable to open the log file "%s" for writing.', $options['file']));
        }

        $this->fp = fopen($options['file'], 'a');
        if (!$fileExists) {
            chmod($options['file'], isset($options['file_mode']) ? $options['file_mode'] : 0666);
        }

        parent::initialize($dispatcher, $options);
    }

    /**
     * Executes the shutdown method.
     */
    public function shutdown()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }
    }

    /**
     * @return false|string
     */
    public static function strftime($format)
    {
        if (version_compare(PHP_VERSION, '8.1.0') < 0) {
            return strftime($format);
        }

        return date(self::_strftimeFormatToDateFormat($format));
    }

    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     */
    protected function doLog($message, $priority)
    {
        flock($this->fp, LOCK_EX);
        fwrite($this->fp, strtr($this->format, [
            '%type%' => $this->type,
            '%message%' => $message,
            '%time%' => self::strftime($this->timeFormat),
            '%priority%' => $this->getPriority($priority),
            '%EOL%' => PHP_EOL,
        ]));
        flock($this->fp, LOCK_UN);
    }

    /**
     * Returns the priority string to use in log messages.
     *
     * @param string $priority The priority constant
     *
     * @return string The priority to use in log messages
     */
    protected function getPriority($priority)
    {
        return \sfLogger::getPriorityName($priority);
    }

    /**
     * Try to Convert a strftime to date format.
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
        $search = [
            '%a', '%A', '%d', '%e', '%u',
            '%w', '%W', '%b', '%h', '%B',
            '%m', '%y', '%Y', '%D', '%F',
            '%x', '%n', '%t', '%H', '%k',
            '%I', '%l', '%M', '%p', '%P',
            '%r' /* %I:%M:%S %p */, '%R' /* %H:%M */, '%S', '%T' /* %H:%M:%S */, '%X', '%z', '%Z',
            '%c', '%s', '%j',
            '%%'];

        $replace = [
            'D', 'l', 'd', 'j', 'N',
            'w', 'W', 'M', 'M', 'F',
            'm', 'y', 'Y', 'm/d/y', 'Y-m-d',
            'm/d/y', "\n", "\t", 'H', 'G',
            'h', 'g', 'i', 'A', 'a',
            'h:i:s A', 'H:i', 's', 'H:i:s', 'H:i:s', 'O', 'T',
            'D M j H:i:s Y' /* Tue Feb 5 00:45:10 2009 */, 'U', 'z',
            '%'];

        return str_replace($search, $replace, $strftimeFormat);
    }
}
