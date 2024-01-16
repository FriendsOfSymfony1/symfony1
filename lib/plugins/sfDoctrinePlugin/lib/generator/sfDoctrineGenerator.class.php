<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Doctrine generator.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineGenerator extends sfModelGenerator
{
    protected $table;

    /**
     * Initializes the current sfGenerator instance.
     *
     * @param sfGeneratorManager $generatorManager A sfGeneratorManager instance
     */
    public function initialize(sfGeneratorManager $generatorManager)
    {
        parent::initialize($generatorManager);

        $this->setGeneratorClass('sfDoctrineModule');
    }

    /**
     * Configures this generator.
     */
    public function configure()
    {
        $this->table = Doctrine_Core::getTable($this->modelClass);

        // load all primary keys
        $this->loadPrimaryKeys();
    }

    /**
     * Returns an array of tables that represents a many to many relationship.
     *
     * A table is considered to be a m2m table if it has 2 foreign keys that are also primary keys.
     *
     * @return array an array of tables
     */
    public function getManyToManyTables()
    {
        $relations = [];
        foreach ($this->table->getRelations() as $relation) {
            if (Doctrine_Relation::MANY === $relation->getType() && isset($relation['refTable'])) {
                $relations[] = $relation;
            }
        }

        return $relations;
    }

    /**
     * Returns the getter either non-developped: 'getFoo' or developped: '$class->getFoo()'.
     *
     * @param string $column    The column name
     * @param bool   $developed true if you want developped method names, false otherwise
     * @param string $prefix    The prefix value
     *
     * @return string PHP code
     */
    public function getColumnGetter($column, $developed = false, $prefix = '')
    {
        $getter = 'get'.sfInflector::camelize($column);
        if ($developed) {
            $getter = sprintf('$%s%s->%s()', $prefix, $this->getSingularName(), $getter);
        }

        return $getter;
    }

    /**
     * Returns the type of a column.
     *
     * @param object $column A column object
     *
     * @return string The column type
     */
    public function getType($column)
    {
        if ($column->isForeignKey()) {
            return 'ForeignKey';
        }

        switch ($column->getDoctrineType()) {
            case 'enum':
                return 'Enum';

            case 'boolean':
                return 'Boolean';

            case 'date':
            case 'timestamp':
                return 'Date';

            case 'time':
                return 'Time';

            default:
                return 'Text';
        }
    }

    /**
     * Returns the default configuration for fields.
     *
     * @return array An array of default configuration for all fields
     */
    public function getDefaultFieldsConfiguration()
    {
        $fields = [];

        $names = [];
        foreach ($this->getColumns() as $name => $column) {
            $names[] = $name;
            $fields[$name] = array_merge([
                'is_link' => (bool) $column->isPrimaryKey(),
                'is_real' => true,
                'is_partial' => false,
                'is_component' => false,
                'type' => $this->getType($column),
            ], isset($this->config['fields'][$name]) ? $this->config['fields'][$name] : []);
        }

        foreach ($this->getManyToManyTables() as $tables) {
            $name = sfInflector::underscore($tables['alias']).'_list';
            $names[] = $name;
            $fields[$name] = array_merge([
                'is_link' => false,
                'is_real' => false,
                'is_partial' => false,
                'is_component' => false,
                'type' => 'Text',
            ], isset($this->config['fields'][$name]) ? $this->config['fields'][$name] : []);
        }

        if (isset($this->config['fields'])) {
            foreach ($this->config['fields'] as $name => $params) {
                if (in_array($name, $names)) {
                    continue;
                }

                $fields[$name] = array_merge([
                    'is_link' => false,
                    'is_real' => false,
                    'is_partial' => false,
                    'is_component' => false,
                    'type' => 'Text',
                ], is_array($params) ? $params : []);
            }
        }

        unset($this->config['fields']);

        return $fields;
    }

    /**
     * Returns the configuration for fields in a given context.
     *
     * @param string $context The Context
     *
     * @return array An array of configuration for all the fields in a given context
     */
    public function getFieldsConfiguration($context)
    {
        $fields = [];

        $names = [];
        foreach ($this->getColumns() as $name => $column) {
            $names[] = $name;
            $fields[$name] = isset($this->config[$context]['fields'][$name]) ? $this->config[$context]['fields'][$name] : [];
        }

        foreach ($this->getManyToManyTables() as $tables) {
            $name = sfInflector::underscore($tables['alias']).'_list';
            $names[] = $name;
            $fields[$name] = isset($this->config[$context]['fields'][$name]) ? $this->config[$context]['fields'][$name] : [];
        }

        if (isset($this->config[$context]['fields'])) {
            foreach ($this->config[$context]['fields'] as $name => $params) {
                if (in_array($name, $names)) {
                    continue;
                }

                $fields[$name] = is_array($params) ? $params : [];
            }
        }

        unset($this->config[$context]['fields']);

        return $fields;
    }

    /**
     * Gets all the fields for the current model.
     *
     * @param bool $withM2M Whether to include m2m fields or not
     *
     * @return array An array of field names
     */
    public function getAllFieldNames($withM2M = true)
    {
        $names = [];
        foreach ($this->getColumns() as $name => $column) {
            $names[] = $name;
        }

        if ($withM2M) {
            foreach ($this->getManyToManyTables() as $tables) {
                $names[] = sfInflector::underscore($tables['alias']).'_list';
            }
        }

        return $names;
    }

    /**
     * Get array of sfDoctrineAdminColumn objects.
     *
     * @return array $columns
     */
    public function getColumns()
    {
        foreach (array_keys($this->table->getColumns()) as $name) {
            $name = $this->table->getFieldName($name);
            $columns[$name] = new sfDoctrineColumn($name, $this->table);
        }

        return $columns;
    }

    /**
     * Loads primary keys.
     *
     * @throws sfException
     */
    protected function loadPrimaryKeys()
    {
        $this->primaryKey = [];
        foreach ($this->getColumns() as $name => $column) {
            if ($column->isPrimaryKey()) {
                $this->primaryKey[] = $name;
            }
        }

        if (!count($this->primaryKey)) {
            throw new sfException(sprintf('Cannot generate a module for a model without a primary key (%s)', $this->modelClass));
        }
    }
}
