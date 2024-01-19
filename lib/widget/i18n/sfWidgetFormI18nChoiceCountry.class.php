<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormI18nChoiceCountry represents a country choice widget.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormI18nChoiceCountry extends sfWidgetFormChoice
{
    /**
     * Constructor.
     *
     * Available options:
     *
     *  * culture:   The culture to use for internationalized strings
     *  * countries: An array of country codes to use (ISO 3166)
     *  * add_empty: Whether to add a first empty value or not (false by default)
     *               If the option is not a Boolean, the value will be used as the text value
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see sfWidgetFormChoice
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->addOption('culture');
        $this->addOption('countries');
        $this->addOption('add_empty', false);

        // populate choices with all countries
        $culture = isset($options['culture']) ? $options['culture'] : 'en';

        $countries = sfCultureInfo::getInstance($culture)->getCountries(isset($options['countries']) ? $options['countries'] : null);

        $addEmpty = isset($options['add_empty']) ? $options['add_empty'] : false;
        if (false !== $addEmpty) {
            $countries = array_merge(['' => true === $addEmpty ? '' : $addEmpty], $countries);
        }

        $this->setOption('choices', $countries);
    }
}
