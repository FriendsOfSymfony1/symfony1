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
 * sfWidgetFormDoctrineChoice represents a choice widget for a model.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormDoctrineChoice extends sfWidgetFormChoice
{
    /**
     * @see sfWidget
     *
     * @param mixed $options
     * @param mixed $attributes
     */
    public function __construct($options = [], $attributes = [])
    {
        $options['choices'] = [];

        parent::__construct($options, $attributes);
    }

    /**
     * Returns the choices associated to the model.
     *
     * @return array An array of choices
     */
    public function getChoices()
    {
        $choices = [];
        if (false !== $this->getOption('add_empty')) {
            $choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
        }

        if (null === $this->getOption('table_method')) {
            $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
            if ($order = $this->getOption('order_by')) {
                $query->addOrderBy($order[0].' '.$order[1]);
            }
            $objects = $query->execute();
        } else {
            $tableMethod = $this->getOption('table_method');
            $results = Doctrine_Core::getTable($this->getOption('model'))->{$tableMethod}();

            if ($results instanceof Doctrine_Query) {
                $objects = $results->execute();
            } elseif ($results instanceof Doctrine_Collection) {
                $objects = $results;
            } elseif ($results instanceof Doctrine_Record) {
                $objects = new Doctrine_Collection($this->getOption('model'));
                $objects[] = $results;
            } else {
                $objects = [];
            }
        }

        $method = $this->getOption('method');
        $keyMethod = $this->getOption('key_method');

        foreach ($objects as $object) {
            $choices[$object->{$keyMethod}()] = $object->{$method}();
        }

        return $choices;
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * model:        The model class (required)
     *  * add_empty:    Whether to add a first empty value or not (false by default)
     *                  If the option is not a Boolean, the value will be used as the text value
     *  * method:       The method to use to display object values (__toString by default)
     *  * key_method:   The method to use to display the object keys (getPrimaryKey by default)
     *  * order_by:     An array composed of two fields:
     *                    * The column to order by the results (must be in the PhpName format)
     *                    * asc or desc
     *  * query:        A query to use when retrieving objects
     *  * multiple:     true if the select tag must allow multiple selections
     *  * table_method: A method to return either a query, collection or single object
     *
     * @see sfWidgetFormSelect
     *
     * @param mixed $options
     * @param mixed $attributes
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->addRequiredOption('model');
        $this->addOption('add_empty', false);
        $this->addOption('method', '__toString');
        $this->addOption('key_method', 'getPrimaryKey');
        $this->addOption('order_by', null);
        $this->addOption('query', null);
        $this->addOption('multiple', false);
        $this->addOption('table_method', null);

        parent::configure($options, $attributes);
    }
}
