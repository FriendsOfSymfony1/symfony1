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
 * sfDoctrineRecordI18nFilter implements access to the translated properties for
 * the current culture from the internationalized model.
 *
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineRecordI18nFilter extends \Doctrine_Record_Filter
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
    public function filterSet(\Doctrine_Record $record, $name, $value)
    {
        return $record['Translation'][\sfDoctrineRecord::getDefaultCulture()][$name] = $value;
    }

    /**
     * Call get on Translation relationship.
     *
     * Allow access to I18n properties from the main object.
     *
     * @param string $name Name of the property
     */
    public function filterGet(\Doctrine_Record $record, $name)
    {
        $culture = \sfDoctrineRecord::getDefaultCulture();
        if (isset($record['Translation'][$culture]) && '' != $record['Translation'][$culture][$name]) {
            return $record['Translation'][$culture][$name];
        }

        $defaultCulture = \sfConfig::get('sf_default_culture');

        return $record['Translation'][$defaultCulture][$name];
    }
}
