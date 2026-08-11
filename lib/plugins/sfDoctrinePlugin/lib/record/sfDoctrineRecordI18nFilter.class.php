<?php

/*
 * This file is part of the symfony package.
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineRecordI18nFilter implements access to the translated properties for
 * the current culture from the internationalized model.
 *
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 */
class sfDoctrineRecordI18nFilter extends Doctrine_Record_Filter
{
    /**
     * @see Doctrine_Table::unshiftFilter()
     */
    public function init()
    {
    }

    /**
     * Calls set on Translation relationship.
     *
     * Allows manipulation of I18n properties from the main object.
     *
     * @param string $name  Name of the property
     * @param string $value Value of the property
     */
    public function filterSet(Doctrine_Record $record, $name, $value)
    {
        return $record['Translation'][sfDoctrineRecord::getDefaultCulture()][$name] = $value;
    }

    /**
     * Call get on Translation relationship.
     *
     * Allow access to I18n properties from the main object.
     *
     * @param string $name Name of the property
     */
    public function filterGet(Doctrine_Record $record, $name)
    {
        $culture = sfDoctrineRecord::getDefaultCulture();

        // Access Translation relation explicitly to trigger lazy loading.
        // PHP's isset() with chained array access like isset($record['Translation'][$culture])
        // does not trigger __get() on the intermediate $record['Translation'] access,
        // causing the check to incorrectly return false on first access after clear().
        $translation = $record['Translation'];

        if (isset($translation[$culture]) && '' != $translation[$culture][$name]) {
            return $translation[$culture][$name];
        }

        $defaultCulture = sfConfig::get('sf_default_culture');

        return $translation[$defaultCulture][$name];
    }
}
