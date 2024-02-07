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
 * Base sfDoctrineRecord extends the base Doctrine_Record in Doctrine to provide some
 * symfony specific functionality to Doctrine_Records.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfDoctrineRecord extends \Doctrine_Record
{
    protected static $_defaultCulture = 'en';

    /**
     * Returns a string representation of the record.
     *
     * @return string A string representation of the record
     */
    public function __toString()
    {
        $guesses = ['name',
            'title',
            'description',
            'subject',
            'keywords',
            'id'];

        // we try to guess a column which would give a good description of the object
        foreach ($guesses as $descriptionColumn) {
            try {
                return (string) $this->get($descriptionColumn);
            } catch (\Exception $e) {
            }
        }

        return sprintf('No description for object of class "%s"', $this->getTable()->getComponentName());
    }

    /**
     * Provides getter and setter methods.
     *
     * @param string $method    The method name
     * @param array  $arguments The method arguments
     *
     * @return mixed The returned value of the called method
     */
    public function __call($method, $arguments)
    {
        $failed = false;

        try {
            if (in_array($verb = substr($method, 0, 3), ['set', 'get'])) {
                $name = substr($method, 3);

                $table = $this->getTable();
                if ($table->hasRelation($name)) {
                    $entityName = $name;
                } elseif ($table->hasField($fieldName = $table->getFieldName($name))) {
                    $entityNameLower = strtolower($fieldName);
                    if ($table->hasField($entityNameLower)) {
                        $entityName = $entityNameLower;
                    } else {
                        $entityName = $fieldName;
                    }
                } else {
                    $underScored = $table->getFieldName(\sfInflector::underscore($name));
                    if ($table->hasField($underScored) || $table->hasRelation($underScored)) {
                        $entityName = $underScored;
                    } elseif ($table->hasField(strtolower($name)) || $table->hasRelation(strtolower($name))) {
                        $entityName = strtolower($name);
                    } else {
                        $camelCase = $table->getFieldName(\sfInflector::camelize($name));
                        $camelCase = strtolower($camelCase[0]).substr($camelCase, 1, strlen($camelCase));
                        if ($table->hasField($camelCase) || $table->hasRelation($camelCase)) {
                            $entityName = $camelCase;
                        } else {
                            $entityName = $underScored;
                        }
                    }
                }

                return call_user_func_array(
                    [$this, $verb],
                    array_merge([$entityName], $arguments)
                );
            }
            $failed = true;
        } catch (\Exception $e) {
            $failed = true;
        }
        if ($failed) {
            try {
                return parent::__call($method, $arguments);
            } catch (\Doctrine_Record_UnknownPropertyException $e2) {
            }

            if (isset($e) && $e) {
                throw $e;
            }
            if (isset($e2) && $e2) {
                throw $e2;
            }
        }
    }

    /**
     * Initializes internationalization.
     *
     * @see \Doctrine_Record
     */
    public function construct()
    {
        if ($this->getTable()->hasRelation('Translation')) {
            // only add filter to each table once
            if (!$this->getTable()->getOption('has_symfony_i18n_filter')) {
                $this->getTable()
                    ->unshiftFilter(new \sfDoctrineRecordI18nFilter())
                    ->setOption('has_symfony_i18n_filter', true)
                ;
            }
        }
    }

    /**
     * Listens to the user.change_culture event.
     *
     * @param \sfEvent An sfEvent instance
     */
    public static function listenToChangeCultureEvent(\sfEvent $event)
    {
        self::$_defaultCulture = $event['culture'];
    }

    /**
     * Sets the default culture.
     *
     * @param string $culture
     */
    public static function setDefaultCulture($culture)
    {
        self::$_defaultCulture = $culture;
    }

    /**
     * Return the default culture.
     *
     * @return string the default culture
     */
    public static function getDefaultCulture()
    {
        if (!self::$_defaultCulture) {
            throw new \sfException('The default culture has not been set');
        }

        return self::$_defaultCulture;
    }

    /**
     * Returns the current record's primary key.
     *
     * This a proxy method to {@link Doctrine_Record::identifier()} for
     * compatibility with a Propel-style API.
     *
     * @return mixed The value of the current model's last identifier column
     */
    public function getPrimaryKey()
    {
        $identifier = (array) $this->identifier();

        return end($identifier);
    }

    /**
     * Function require by symfony >= 1.2 admin generators.
     *
     * @return bool
     */
    public function isNew()
    {
        return !$this->exists();
    }

    /**
     * Get the Doctrine date value as a PHP DateTime object, null if the value is not set.
     *
     * @param string $dateFieldName The field name to get the DateTime object for
     *
     * @return \DateTime|null $dateTime     The instance of PHPs DateTime
     *
     * @throws \sfException if the field is not one of date, datetime, or timestamp types
     */
    public function getDateTimeObject($dateFieldName)
    {
        $type = $this->getTable()->getTypeOf($dateFieldName);
        if ('date' == $type || 'timestamp' == $type || 'datetime' == $type) {
            $datetime = $this->get($dateFieldName);
            if ($datetime) {
                return new \DateTime($datetime);
            }
        } else {
            throw new \sfException('Cannot call getDateTimeObject() on a field that is not of type date or timestamp.');
        }
    }

    /**
     * Set the Doctrine date value by passing a valid PHP DateTime object instance.
     *
     * @param string    $dateFieldName  The field name to set the date for
     * @param \DateTime $dateTimeObject The DateTime instance to use to set the value
     *
     * @return \sfDoctrineRecord
     *
     * @throws \sfException if the field is not one of date, datetime, or timestamp types
     */
    public function setDateTimeObject($dateFieldName, \DateTime $dateTimeObject = null)
    {
        $type = $this->getTable()->getTypeOf($dateFieldName);
        if ('date' == $type || 'timestamp' == $type || 'datetime' == $type) {
            if (null === $dateTimeObject) {
                return $this->set($dateFieldName, null);
            }

            return $this->set($dateFieldName, $dateTimeObject->format('Y-m-d H:i:s'));
        }

        throw new \sfException('Cannot call setDateTimeObject() on a field that is not of type date or timestamp.');
    }
}
