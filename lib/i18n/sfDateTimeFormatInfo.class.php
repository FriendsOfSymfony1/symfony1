<?php

/**
 * sfDateTimeFormatInfo class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author     Wei Zhuo <weizhuo[at]gmail[dot]com>
 *
 * @version    $Id$
 */

/**
 * Defines how DateTime values are formatted and displayed, depending
 * on the culture.
 *
 * This class contains information, such as date patterns, time patterns,
 * and AM/PM designators.
 *
 * To create a sfDateTimeFormatInfo for a specific culture, create a
 * sfCultureInfo for that culture and retrieve the sfCultureInfo.sfDateTimeFormat
 * property. For example:
 * <code>
 * $culture = new sfCultureInfo('en_AU');
 * $dtfi = $culture->DateTimeFormat;
 * </code>
 *
 * To create a sfDateTimeFormatInfo for the invariant culture, use
 * <code>
 * sfDateTimeFormatInfo::getInstance($culture=null);
 * </code>
 * you may pass a sfCultureInfo parameter $culture to get the sfDateTimeFormatInfo
 * for a specific culture.
 *
 * sfDateTime values are formatted using standard or custom patterns stored in
 * the properties of a sfDateTimeFormatInfo.
 *
 * The standard patterns can be replaced with custom patterns by setting the
 * associated properties of sfDateTimeFormatInfo.
 *
 * The following table lists the standard format characters for each standard
 * pattern and the associated sfDateTimeFormatInfo property that can be set to
 * modify the standard pattern. The format characters are case-sensitive;
 * for example, 'g' and 'G' represent slightly different patterns.
 *
 * <code>
 *  Format Character    Associated Property     Example Format Pattern (en-US)
 *  --------------------------------------------------------------------------
 *  d                   ShortDatePattern        MM/dd/yyyy
 *  D                   LongDatePattern         dddd, dd MMMM yyyy
 *  F                   FullDateTimePattern     dddd, dd MMMM yyyy HH:mm:ss
 *  m, M                MonthDayPattern         MMMM dd
 *  r, R                RFC1123Pattern          ddd, dd MMM yyyy HH':'mm':'ss 'GMT'
 *  s                   SortableDateTimePattern yyyy'-'MM'-'dd'T'HH':'mm':'ss
 *  t                   ShortTimePattern        HH:mm
 *  T                   LongTimePattern         HH:mm:ss
 *  Y                   YearMonthPattern        yyyy MMMM
 *  --------------------------------------------------------------------------
 * </code>
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 *
 * @version v1.0, last update on Fri Dec 03 22:30:31 EST 2004
 */
class sfDateTimeFormatInfo
{
    /**
     * ICU date time formatting data.
     */
    protected $data = [];

    /**
     * A list of properties that are accessable/writable.
     */
    protected $properties = [];

    /**
     * Initializes a new writable instance of the sfDateTimeFormatInfo class
     * that is dependent on the ICU data for date time formatting
     * information. <b>N.B.</b>You should not initialize this class directly
     * unless you know what you are doing. Please use use
     * sfDateTimeFormatInfo::getInstance() to create an instance.
     *
     * @param array $data ICU data for date time formatting
     *
     * @see getInstance()
     */
    public function __construct($data = [])
    {
        $this->properties = get_class_methods($this);

        if (empty($data)) {
            throw new sfException('Please provide the ICU data to initialize.');
        }

        $this->data = $data;
    }

    /**
     * Allows functions that begins with 'set' to be called directly
     * as an attribute/property to retrieve the value.
     *
     * @param mixed $name
     */
    public function __get($name)
    {
        $getProperty = 'get'.$name;
        if (in_array($getProperty, $this->properties)) {
            return $this->{$getProperty}();
        }

        throw new sfException(sprintf('Property %s does not exists.', $name));
    }

    /**
     * Allows functions that begins with 'set' to be called directly
     * as an attribute/property to set the value.
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $setProperty = 'set'.$name;
        if (in_array($setProperty, $this->properties)) {
            $this->{$setProperty}($value);
        } else {
            throw new sfException(sprintf('Property %s can not be set.', $name));
        }
    }

    /**
     * Gets the default sfDateTimeFormatInfo that is culture-independent (invariant).
     *
     * @return sfDateTimeFormatInfo default sfDateTimeFormatInfo
     */
    public static function getInvariantInfo()
    {
        static $invariant;

        if (null === $invariant) {
            $invariant = sfCultureInfo::getInvariantCulture()->DateTimeFormat;
        }

        return $invariant;
    }

    /**
     * Returns the sfDateTimeFormatInfo associated with the specified culture.
     *
     * @param sfCultureInfo $culture the culture that gets the sfDateTimeFormat property
     *
     * @return sfDateTimeFormatInfo sfDateTimeFormatInfo for the specified
     *                              culture
     */
    public static function getInstance($culture = null)
    {
        if ($culture instanceof sfCultureInfo) {
            return $culture->DateTimeFormat;
        }
        if (is_string($culture)) {
            return sfCultureInfo::getInstance($culture)->DateTimeFormat;
        }

        return sfCultureInfo::getInvariantCulture()->DateTimeFormat;
    }

    /**
     * A one-dimensional array of type String containing
     * the culture-specific abbreviated names of the days
     * of the week. The array for InvariantInfo contains
     * "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", and "Sat".
     *
     * @return array abbreviated day names
     */
    public function getAbbreviatedDayNames()
    {
        return $this->data['dayNames']['format']['abbreviated'];
    }

    /**
     * Sets the abbreviated day names. The value should be
     * an array of string starting with Sunday and ends in Saturady.
     * For example,
     * <code>array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");</code>.
     *
     * @param array $value abbreviated day names
     */
    public function setAbbreviatedDayNames($value)
    {
        $this->data['dayNames']['format']['abbreviated'] = $value;
    }

    /**
     * A one-dimensional array of type String containing
     * the culture-specific narrow names of the days
     * of the week. The array for InvariantInfo contains
     * "S", "M", "T", "W", "T", "F", and "S".
     *
     * @return array narrow day names
     */
    public function getNarrowDayNames()
    {
        return $this->data['dayNames']['stand-alone']['narrow'];
    }

    /**
     * Sets the narrow day names. The value should be
     * an array of string starting with Sunday and ends in Saturady.
     * For example,
     * <code>array("S", "M", "T", "W", "T", "F", "S");</code>.
     *
     * @param array $value narrow day names
     */
    public function setNarrowDayNames($value)
    {
        $this->data['dayNames']['stand-alone']['narrow'] = $value;
    }

    /**
     * A one-dimensional array of type String containing the
     * culture-specific full names of the days of the week.
     * The array for InvariantInfo contains "Sunday", "Monday",
     * "Tuesday", "Wednesday", "Thursday", "Friday", and "Saturday".
     *
     * @return array day names
     */
    public function getDayNames()
    {
        return $this->data['dayNames']['format']['wide'];
    }

    /**
     * Sets the day names. The value should be
     * an array of string starting with Sunday and ends in Saturady.
     * For example,
     * <code>array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
     * "Friday", "Saturday".);</code>.
     *
     * @param array $value day names
     */
    public function setDayNames($value)
    {
        $this->data['dayNames']['format']['wide'] = $value;
    }

    /**
     * A one-dimensional array of type String containing the
     * culture-specific narrow names of the months. The array
     * for InvariantInfo contains "J", "F", "M", "A", "M", "J",
     * "J", "A", "S", "O", "N", and "D".
     *
     * @return array narrow month names
     */
    public function getNarrowMonthNames()
    {
        return $this->data['monthNames']['stand-alone']['narrow'];
    }

    /**
     * Sets the narrow month names. The value should be
     * an array of string starting with J and ends in D.
     * For example,
     * <code>array("J","F","M","A","M","J","J","A","S","O","N","D");</code>.
     *
     * @param array $value month names
     */
    public function setNarrowMonthNames($value)
    {
        $this->data['monthNames']['stand-alone']['narrow'] = $value;
    }

    /**
     * A one-dimensional array of type String containing the
     * culture-specific abbreviated names of the months. The array
     * for InvariantInfo contains "Jan", "Feb", "Mar", "Apr", "May",
     * "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", and "Dec".
     *
     * Returns wide names if abbreviated names doesn't exist.
     *
     * @return array abbreviated month names
     */
    public function getAbbreviatedMonthNames()
    {
        if (isset($this->data['monthNames']['format']['abbreviated'])) {
            return $this->data['monthNames']['format']['abbreviated'];
        }

        return $this->data['monthNames']['format']['wide'];
    }

    /**
     * Sets the abbreviated month names. The value should be
     * an array of string starting with Jan and ends in Dec.
     * For example,
     * <code>array("Jan", "Feb", "Mar", "Apr", "May", "Jun",
     * "Jul", "Aug", "Sep","Oct","Nov","Dec");</code>.
     *
     * @param array $value month names
     */
    public function setAbbreviatedMonthNames($value)
    {
        $this->data['monthNames']['format']['abbreviated'] = $value;
    }

    /**
     * A one-dimensional array of type String containing the
     * culture-specific full names of the months. The array for
     * InvariantInfo contains "January", "February", "March", "April",
     * "May", "June", "July", "August", "September", "October", "November",
     * and "December".
     *
     * @return array month names
     */
    public function getMonthNames()
    {
        return $this->data['monthNames']['format']['wide'];
    }

    /**
     * Sets the month names. The value should be
     * an array of string starting with Janurary and ends in December.
     * For example,
     * <code>array("January", "February", "March", "April", "May", "June",
     * "July", "August", "September","October","November","December");</code>.
     *
     * @param array $value month names
     */
    public function setMonthNames($value)
    {
        $this->data['monthNames']['format']['wide'] = $value;
    }

    /**
     * A string containing the name of the era.
     *
     * @param int $era era The integer representing the era
     *
     * @return string the era name
     */
    public function getEra($era)
    {
        return $this->data['eras']['abbreviated'][$era];
    }

    /**
     * The string designator for hours that are "ante meridiem" (before noon).
     * The default for InvariantInfo is "AM".
     *
     * @return string AM designator
     */
    public function getAMDesignator()
    {
        $result = $this->getAMPMMarkers();

        return $result[0];
    }

    /**
     * Sets the AM Designator. For example, 'AM'.
     *
     * @param string $value AM designator
     */
    public function setAMDesignator($value)
    {
        $markers = $this->getAMPMMarkers();
        $markers[0] = $value;
        $this->setAMPMMarkers($markers);
    }

    /**
     * The string designator for hours that are "post meridiem" (after noon).
     * The default for InvariantInfo is "PM".
     *
     * @return string PM designator
     */
    public function getPMDesignator()
    {
        $result = $this->getAMPMMarkers();

        return $result[1];
    }

    /**
     * Sets the PM Designator. For example, 'PM'.
     *
     * @param string $value PM designator
     */
    public function setPMDesignator($value)
    {
        $markers = $this->getAMPMMarkers();
        $markers[1] = $value;
        $this->setAMPMMarkers($markers);
    }

    /**
     * Gets the AM and PM markers array.
     * Default InvariantInfo for AM and PM is <code>array('AM','PM');</code>.
     *
     * @return array AM and PM markers
     */
    public function getAMPMMarkers()
    {
        return $this->data['AmPmMarkers'];
    }

    /**
     * Sets the AM and PM markers array.
     * For example <code>array('AM','PM');</code>.
     *
     * @param array $value AM and PM markers
     */
    public function setAMPMMarkers($value)
    {
        $this->data['AmPmMarkers'] = $value;
    }

    /**
     * Returns the full time pattern "HH:mm:ss z" (default).
     * This is culture sensitive.
     *
     * @return string pattern "HH:mm:ss z"
     */
    public function getFullTimePattern()
    {
        return $this->data['DateTimePatterns'][0];
    }

    /**
     * Returns the long time pattern "HH:mm:ss z" (default).
     * This is culture sensitive.
     *
     * @return string pattern "HH:mm:ss z"
     */
    public function getLongTimePattern()
    {
        return $this->data['DateTimePatterns'][1];
    }

    /**
     * Returns the medium time pattern "HH:mm:ss" (default).
     * This is culture sensitive.
     *
     * @return string pattern "HH:mm:ss"
     */
    public function getMediumTimePattern()
    {
        return $this->data['DateTimePatterns'][2];
    }

    /**
     * Returns the short time pattern "HH:mm" (default).
     * This is culture sensitive.
     *
     * @return string pattern "HH:mm"
     */
    public function getShortTimePattern()
    {
        return $this->data['DateTimePatterns'][3];
    }

    /**
     * Returns the full date pattern "EEEE, yyyy MMMM dd" (default).
     * This is culture sensitive.
     *
     * @return string pattern "EEEE, yyyy MMMM dd"
     */
    public function getFullDatePattern()
    {
        return $this->data['DateTimePatterns'][4];
    }

    /**
     * Returns the long date pattern "yyyy MMMM d" (default).
     * This is culture sensitive.
     *
     * @return string pattern "yyyy MMMM d"
     */
    public function getLongDatePattern()
    {
        return $this->data['DateTimePatterns'][5];
    }

    /**
     * Returns the medium date pattern "yyyy MMMM d" (default).
     * This is culture sensitive.
     *
     * @return string pattern "yyyy MMM d"
     */
    public function getMediumDatePattern()
    {
        return $this->data['DateTimePatterns'][6];
    }

    /**
     * Returns the short date pattern "yy/MM/dd" (default).
     * This is culture sensitive.
     *
     * @return string pattern "yy/MM/dd"
     */
    public function getShortDatePattern()
    {
        return $this->data['DateTimePatterns'][7];
    }

    /**
     * Returns the date time order pattern, "{1} {0}" (default).
     * This is culture sensitive.
     *
     * @return string pattern "{1} {0}"
     */
    public function getDateTimeOrderPattern()
    {
        return $this->data['DateTimePatterns'][8];
    }

    /**
     * Formats the date and time in a culture sensitive paterrn.
     * The default is "Date Time".
     *
     * @param mixed $date
     * @param mixed $time
     *
     * @return string date and time formated
     */
    public function formatDateTime($date, $time)
    {
        return str_replace(['{0}', '{1}'], [$time, $date], $this->getDateTimeOrderPattern());
    }

    /**
     * Gets the internal ICU data for date time formatting.
     *
     * @return array ICU date time formatting data
     */
    protected function getData()
    {
        return $this->data;
    }
}
