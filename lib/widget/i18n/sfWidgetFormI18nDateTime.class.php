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
 * sfWidgetFormI18nDateTime represents a date and time widget.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormI18nDateTime extends \sfWidgetFormDateTime
{
    /**
     * Constructor.
     *
     * Available options:
     *
     *  * culture: The culture to use for internationalized strings (required)
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetFormDateTime
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addRequiredOption('culture');

        $culture = isset($options['culture']) ? $options['culture'] : 'en';

        // format
        $this->setOption('format', str_replace(['{0}', '{1}'], ['%time%', '%date%'], \sfDateTimeFormatInfo::getInstance($culture)->getDateTimeOrderPattern()));
    }

    /**
     * @see \sfWidgetFormDateTime
     */
    protected function getDateWidget($attributes = [])
    {
        return new \sfWidgetFormI18nDate(array_merge(['culture' => $this->getOption('culture')], $this->getOptionsFor('date')), $this->getAttributesFor('date', $attributes));
    }

    /**
     * @see \sfWidgetFormDateTime
     */
    protected function getTimeWidget($attributes = [])
    {
        return new \sfWidgetFormI18nTime(array_merge(['culture' => $this->getOption('culture')], $this->getOptionsFor('time')), $this->getAttributesFor('time', $attributes));
    }
}
