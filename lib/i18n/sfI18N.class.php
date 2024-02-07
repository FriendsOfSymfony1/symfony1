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
 * sfI18N wraps the core i18n classes for a symfony context.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfI18N
{
    protected $configuration;
    protected $dispatcher;
    protected $cache;
    protected $options = [];
    protected $culture = 'en';
    protected $messageSource;
    protected $messageFormat;

    /**
     * Class constructor.
     *
     * @see initialize()
     */
    public function __construct(\sfApplicationConfiguration $configuration, \sfCache $cache = null, $options = [])
    {
        $this->initialize($configuration, $cache, $options);
    }

    /**
     * Gets the translation for the given string.
     *
     * @param string $string    The string to translate
     * @param array  $args      An array of arguments for the translation
     * @param string $catalogue The catalogue name
     *
     * @return string The translated string
     */
    public function __($string, $args = [], $catalogue = 'messages')
    {
        return $this->getMessageFormat()->format($string, $args, $catalogue);
    }

    /**
     * Initializes this class.
     *
     * Available options:
     *
     *  * culture:             The culture
     *  * source:              The i18n source (XLIFF by default)
     *  * debug:               Whether to enable debug or not (false by default)
     *  * database:            The database name (default by default)
     *  * untranslated_prefix: The prefix to use when a message is not translated
     *  * untranslated_suffix: The suffix to use when a message is not translated
     *
     * @param \sfApplicationConfiguration $configuration A sfApplicationConfiguration instance
     * @param \sfCache                    $cache         A sfCache instance
     * @param array                       $options       An array of options
     */
    public function initialize(\sfApplicationConfiguration $configuration, \sfCache $cache = null, $options = [])
    {
        $this->configuration = $configuration;
        $this->dispatcher = $configuration->getEventDispatcher();
        $this->cache = $cache;

        if (isset($options['culture'])) {
            $this->setCulture($options['culture']);
            unset($options['culture']);
        }

        $this->options = array_merge([
            'source' => 'XLIFF',
            'debug' => false,
            'database' => 'default',
            'untranslated_prefix' => '[T]',
            'untranslated_suffix' => '[/T]',
        ], $options);

        $this->dispatcher->connect('user.change_culture', [$this, 'listenToChangeCultureEvent']);

        if ($this->isMessageSourceFileBased($this->options['source'])) {
            $this->dispatcher->connect('controller.change_action', [$this, 'listenToChangeActionEvent']);
        }
    }

    /**
     * Returns the initialization options.
     *
     * @return array The options used to initialize sfI18n
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns the configuration instance.
     *
     * @return \sfApplicationConfiguration An sfApplicationConfiguration instance
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Sets the message source.
     *
     * @param mixed  $dirs    An array of i18n directories if message source is a sfMessageSource_File subclass, null otherwise
     * @param string $culture The culture
     */
    public function setMessageSource($dirs, $culture = null)
    {
        if (null === $dirs) {
            $this->messageSource = $this->createMessageSource();
        } else {
            $this->messageSource = \sfMessageSource::factory('Aggregate', array_map([$this, 'createMessageSource'], $dirs));
        }

        if (null !== $this->cache) {
            $this->messageSource->setCache($this->cache);
        }

        if (null !== $culture) {
            $this->setCulture($culture);
        } else {
            $this->messageSource->setCulture($this->culture);
        }

        $this->messageFormat = null;
    }

    /**
     * Returns a new message source.
     *
     * @param mixed $dir An array of i18n directories to create a XLIFF or gettext message source, null otherwise
     *
     * @return \sfMessageSource A sfMessageSource object
     */
    public function createMessageSource($dir = null)
    {
        return \sfMessageSource::factory($this->options['source'], self::isMessageSourceFileBased($this->options['source']) ? $dir : $this->options['database']);
    }

    /**
     * Gets the current culture for i18n format objects.
     *
     * @return string The culture
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * Sets the current culture for i18n format objects.
     *
     * @param string $culture The culture
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;

        // change user locale for formatting, collation, and internal error messages
        setlocale(LC_ALL, 'en_US.utf8', 'en_US.UTF8', 'en_US.utf-8', 'en_US.UTF-8');
        setlocale(LC_COLLATE, $culture.'.utf8', $culture.'.UTF8', $culture.'.utf-8', $culture.'.UTF-8');
        setlocale(LC_CTYPE, $culture.'.utf8', $culture.'.UTF8', $culture.'.utf-8', $culture.'.UTF-8');
        setlocale(LC_MONETARY, $culture.'.utf8', $culture.'.UTF8', $culture.'.utf-8', $culture.'.UTF-8');
        setlocale(LC_TIME, $culture.'.utf8', $culture.'.UTF8', $culture.'.utf-8', $culture.'.UTF-8');

        if ($this->messageSource) {
            $this->messageSource->setCulture($culture);
            $this->messageFormat = null;
        }
    }

    /**
     * Gets the message source.
     *
     * @return \sfMessageSource A sfMessageSource object
     */
    public function getMessageSource()
    {
        if (!isset($this->messageSource)) {
            $dirs = ($this->isMessageSourceFileBased($this->options['source'])) ? $this->configuration->getI18NGlobalDirs() : null;
            $this->setMessageSource($dirs, $this->culture);
        }

        return $this->messageSource;
    }

    /**
     * Gets the message format.
     *
     * @return \sfMessageFormat A sfMessageFormat object
     */
    public function getMessageFormat()
    {
        if (!isset($this->messageFormat)) {
            $this->messageFormat = new \sfMessageFormat($this->getMessageSource(), \sfConfig::get('sf_charset'));

            if ($this->options['debug']) {
                $this->messageFormat->setUntranslatedPS([$this->options['untranslated_prefix'], $this->options['untranslated_suffix']]);
            }
        }

        return $this->messageFormat;
    }

    /**
     * Gets a country name.
     *
     * @param string $iso     The ISO code
     * @param string $culture The culture for the translation
     *
     * @return string The country name
     */
    public function getCountry($iso, $culture = null)
    {
        $c = \sfCultureInfo::getInstance(null === $culture ? $this->culture : $culture);
        $countries = $c->getCountries();

        return (array_key_exists($iso, $countries)) ? $countries[$iso] : '';
    }

    /**
     * Gets a native culture name.
     *
     * @param string $culture The culture
     *
     * @return string The culture name
     */
    public function getNativeName($culture)
    {
        return \sfCultureInfo::getInstance($culture)->getNativeName();
    }

    /**
     * Returns a timestamp from a date with time formatted with a given culture.
     *
     * @param string $dateTime The formatted date with time as string
     * @param string $culture  The culture
     *
     * @return int The timestamp
     */
    public function getTimestampForCulture($dateTime, $culture = null)
    {
        list($day, $month, $year) = $this->getDateForCulture($dateTime, null === $culture ? $this->culture : $culture);
        list($hour, $minute) = $this->getTimeForCulture($dateTime, null === $culture ? $this->culture : $culture);

        // mktime behavior change with php8
        $hour = null !== $hour ? $hour : 0;
        $minute = null !== $minute ? $minute : 0;

        return null === $day ? null : mktime($hour, $minute, 0, $month, $day, $year);
    }

    /**
     * Returns the day, month and year from a date formatted with a given culture.
     *
     * @param string $date    The formatted date as string
     * @param string $culture The culture
     *
     * @return array An array with the day, month and year
     */
    public function getDateForCulture($date, $culture = null)
    {
        if (!$date) {
            return null;
        }

        $dateFormatInfo = @\sfDateTimeFormatInfo::getInstance(null === $culture ? $this->culture : $culture);
        $dateFormat = $dateFormatInfo->getShortDatePattern();

        // We construct the regexp based on date format
        $dateRegexp = preg_replace('/[dmy]+/i', '(\d+)', preg_quote($dateFormat));

        // We parse date format to see where things are (m, d, y)
        $a = [
            'd' => strpos($dateFormat, 'd'),
            'm' => strpos($dateFormat, 'M'),
            'y' => strpos($dateFormat, 'y'),
        ];
        $tmp = array_flip($a);
        ksort($tmp);
        $i = 0;
        $c = [];
        foreach ($tmp as $value) {
            $c[++$i] = $value;
        }
        $datePositions = array_flip($c);

        // We find all elements
        if (preg_match("~{$dateRegexp}~", $date, $matches)) {
            // We get matching timestamp
            return [$matches[$datePositions['d']], $matches[$datePositions['m']], $matches[$datePositions['y']]];
        }

        return null;
    }

    /**
     * Returns the hour, minute from a date formatted with a given culture.
     *
     * @param string $time    The formatted date as string
     * @param string $culture The culture
     *
     * @return array An array with the hour and minute
     */
    public function getTimeForCulture($time, $culture = null)
    {
        if (!$time) {
            return 0;
        }

        $culture = null === $culture ? $this->culture : $culture;

        $timeFormatInfo = @\sfDateTimeFormatInfo::getInstance($culture);
        $timeFormat = $timeFormatInfo->getShortTimePattern();

        // We construct the regexp based on time format
        $timeRegexp = preg_replace(['/[hm]+/i', '/a/'], ['(\d+)', '(\w+)'], preg_quote($timeFormat));

        // We parse time format to see where things are (h, m)
        $timePositions = [
            'h' =>   str_contains($timeFormat, 'H') ? strpos($timeFormat, 'H') : strpos($timeFormat, 'h'),
            'm' => strpos($timeFormat, 'm'),
            'a' => strpos($timeFormat, 'a'),
        ];
        asort($timePositions);
        $i = 0;

        // normalize positions to 0, 1, ...
        // positions that don't exist in the pattern remain false
        foreach ($timePositions as $key => $value) {
            if (false !== $value) {
                $timePositions[$key] = ++$i;
            }
        }

        // We find all elements
        if (preg_match("~{$timeRegexp}~", $time, $matches)) {
            // repect am/pm setting if present
            if (false !== $timePositions['a']) {
                if (0 == strcasecmp($matches[$timePositions['a']], $timeFormatInfo->getAMDesignator())) {
                    $hour = $matches[$timePositions['h']];
                } elseif (0 == strcasecmp($matches[$timePositions['a']], $timeFormatInfo->getPMDesignator())) {
                    $hour = $matches[$timePositions['h']] + 12;
                } else {
                    // am/pm marker is invalid
                    // return null; would be the preferred solution but this might break a lot of code
                    $hour = $matches[$timePositions['h']];
                }
            } else {
                $hour = $matches[$timePositions['h']];
            }

            // We get matching timestamp
            return [$hour, $matches[$timePositions['m']]];
        }

        return null;
    }

    /**
     * Returns true if messages are stored in a file.
     *
     * @param string $source The source name
     *
     * @return bool true if messages are stored in a file, false otherwise
     */
    public static function isMessageSourceFileBased($source)
    {
        $class = 'sfMessageSource_'.$source;

        return class_exists($class) && is_subclass_of($class, 'sfMessageSource_File');
    }

    /**
     * Listens to the user.change_culture event.
     *
     * @param \sfEvent $event An sfEvent instance
     */
    public function listenToChangeCultureEvent(\sfEvent $event)
    {
        // change the message format object with the new culture
        $this->setCulture($event['culture']);
    }

    /**
     * Listens to the controller.change_action event.
     *
     * @param \sfEvent $event An sfEvent instance
     */
    public function listenToChangeActionEvent(\sfEvent $event)
    {
        // change message source directory to our module
        $this->setMessageSource($this->configuration->getI18NDirs($event['module']));
    }
}
