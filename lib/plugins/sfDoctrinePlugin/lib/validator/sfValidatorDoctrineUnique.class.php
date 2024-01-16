<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorDoctrineUnique validates that the uniqueness of a column.
 *
 * Warning: sfValidatorDoctrineUnique is susceptible to race conditions.
 * To avoid this issue, wrap the validation process and the model saving
 * inside a transaction.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorDoctrineUnique extends sfValidatorSchema
{
    /**
     * Constructor.
     *
     * @param array  An array of options
     * @param array  An array of error messages
     * @param mixed $options
     * @param mixed $messages
     *
     * @see sfValidatorSchema
     */
    public function __construct($options = [], $messages = [])
    {
        parent::__construct(null, $options, $messages);
    }

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * model:              The model class (required)
     *  * column:             The unique column name in Doctrine field name format (required)
     *                        If the uniquess is for several columns, you can pass an array of field names
     *  * primary_key:        The primary key column name in Doctrine field name format (optional, will be introspected if not provided)
     *                        You can also pass an array if the table has several primary keys
     *  * connection:         The Doctrine connection to use (null by default)
     *  * throw_global_error: Whether to throw a global error (false by default) or an error tied to the first field related to the column option array
     *
     * @see sfValidatorBase
     *
     * @param mixed $options
     * @param mixed $messages
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addRequiredOption('model');
        $this->addRequiredOption('column');
        $this->addOption('primary_key', null);
        $this->addOption('connection', null);
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'An object with the same "%column%" already exist.');
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $values
     */
    protected function doClean($values)
    {
        $originalValues = $values;
        $table = Doctrine_Core::getTable($this->getOption('model'));
        if (!is_array($this->getOption('column'))) {
            $this->setOption('column', [$this->getOption('column')]);
        }

        // if $values isn't an array, make it one
        if (!is_array($values)) {
            // use first column for key
            $columns = $this->getOption('column');
            $values = [$columns[0] => $values];
        }

        $q = Doctrine_Core::getTable($this->getOption('model'))->createQuery('a');
        foreach ($this->getOption('column') as $column) {
            $colName = $table->getColumnName($column);
            if (!array_key_exists($column, $values)) {
                // one of the column has be removed from the form
                return $originalValues;
            }

            $q->addWhere('a.'.$colName.' = ?', $values[$column]);
        }

        $object = $q->fetchOne();

        // if no object or if we're updating the object, it's ok
        if (!$object || $this->isUpdate($object, $values)) {
            return $originalValues;
        }

        $error = new sfValidatorError($this, 'invalid', ['column' => implode(', ', $this->getOption('column'))]);

        if ($this->getOption('throw_global_error')) {
            throw $error;
        }

        $columns = $this->getOption('column');

        $errorSchema = new sfValidatorErrorSchema($this);
        $errorSchema->addError($error, $columns[0]);

        throw $errorSchema;
    }

    /**
     * Returns whether the object is being updated.
     *
     * @param BaseObject  A Doctrine object
     * @param array       An array of values
     * @param bool     true if the object is being updated, false otherwise
     * @param mixed $values
     */
    protected function isUpdate(Doctrine_Record $object, $values)
    {
        // check each primary key column
        foreach ($this->getPrimaryKeys() as $column) {
            if (!isset($values[$column]) || $object->{$column} != $values[$column]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the primary keys for the model.
     *
     * @return array An array of primary keys
     */
    protected function getPrimaryKeys()
    {
        if (null === $this->getOption('primary_key')) {
            $primaryKeys = Doctrine_Core::getTable($this->getOption('model'))->getIdentifier();
            $this->setOption('primary_key', $primaryKeys);
        }

        if (!is_array($this->getOption('primary_key'))) {
            $this->setOption('primary_key', [$this->getOption('primary_key')]);
        }

        return $this->getOption('primary_key');
    }
}
