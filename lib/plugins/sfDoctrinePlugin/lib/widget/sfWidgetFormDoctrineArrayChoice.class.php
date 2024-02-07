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
 * sfWidgetFormDoctrineArrayChoice represents a choice widget for a model and table_method.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormDoctrineArrayChoice extends \sfWidgetFormChoice
{
    /**
     * @see \sfWidget
     */
    public function __construct($options = [], $attributes = [])
    {
        $options['choices'] = [];

        parent::__construct($options, $attributes);
    }

    /**
     * Returns the choices builded by the table method of the model.
     *
     * @return array An array of choices
     */
    public function getChoices()
    {
        $choices = [];
        if (false !== $this->getOption('add_empty')) {
            $choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
        }

        $tableMethod = $this->getOption('table_method');
        $table = \Doctrine_Core::getTable($this->getOption('model'));

        if (null !== $params = $this->getOption('table_method_params')) {
            $choices += call_user_func_array([$table, $tableMethod], (array) $params);
        } else {
            $choices += $table->{$tableMethod}();
        }

        return $choices;
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * model:                The model class (required)
     *  * table_method:         A method to return a formatted array of key => value (required)
     *  * table_method_params:  An array of parameters to pass to the table_method
     *  * add_empty:            Whether to add a first empty value or not (false by default)
     *                          If the option is not a Boolean, the value will be used as the text value
     *  * multiple:             true if the select tag must allow multiple selections (false by default)
     *
     * @see \sfWidgetFormChoice
     */
    protected function configure($options = [], $attributes = [])
    {
        $this->addRequiredOption('model');
        $this->addRequiredOption('table_method');
        $this->addOption('table_method_params', null);
        $this->addOption('add_empty', false);
        $this->addOption('multiple', false);

        parent::configure($options, $attributes);
    }
}
