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
 * sfValidatorI18nChoiceCountry validates than the value is a valid country.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorI18nChoiceCountry extends \sfValidatorChoice
{
    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * countries: An array of country codes to use (ISO 3166)
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see \sfValidatorChoice
     */
    protected function configure($options = [], $messages = [])
    {
        parent::configure($options, $messages);

        $this->addOption('countries');

        // populate choices with all countries
        $countries = array_keys(\sfCultureInfo::getInstance()->getCountries());

        // restrict countries to a sub-set
        if (isset($options['countries'])) {
            if ($problems = array_diff($options['countries'], $countries)) {
                throw new \InvalidArgumentException(sprintf('The following countries do not exist: %s.', implode(', ', $problems)));
            }

            $countries = $options['countries'];
        }

        $this->setOption('choices', $countries);
    }
}
