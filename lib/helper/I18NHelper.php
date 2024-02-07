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
 * I18NHelper.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
function __($text, $args = [], $catalogue = 'messages')
{
    if (\sfConfig::get('sf_i18n')) {
        return \sfContext::getInstance()->getI18N()->__($text, $args, $catalogue);
    }

    if (empty($args)) {
        $args = [];
    }

    // replace object with strings
    foreach ($args as $key => $value) {
        if (is_object($value) && method_exists($value, '__toString')) {
            $args[$key] = $value->__toString();
        }
    }

    return strtr($text, $args);
}

/**
 * Format a string according to a number.
 *
 * Every segment is separated with |
 * Each segment defines an intervale and a value.
 *
 * For example :
 *
 * * [0]Nobody is logged|[1]There is 1 person logged|(1,+Inf]There are %number persons logged
 *
 * @param string $text      Text used for different number values
 * @param array  $args      Arguments to replace in the string
 * @param int    $number    Number to use to determine the string to use
 * @param string $catalogue Catalogue for translation
 *
 * @return string Result of the translation
 */
function format_number_choice($text, $args = [], $number = null, $catalogue = 'messages')
{
    $translated = __($text, $args, $catalogue);

    $choice = new \sfChoiceFormat();

    $retval = $choice->format($translated, $number);

    if (false === $retval) {
        throw new \sfException(sprintf('Unable to parse your choice "%s".', $translated));
    }

    return $retval;
}

function format_country($country_iso, $culture = null)
{
    $c = \sfCultureInfo::getInstance(null === $culture ? \sfContext::getInstance()->getUser()->getCulture() : $culture);
    $countries = $c->getCountries();

    return isset($countries[$country_iso]) ? $countries[$country_iso] : '';
}

function format_language($language_iso, $culture = null)
{
    $c = \sfCultureInfo::getInstance(null === $culture ? \sfContext::getInstance()->getUser()->getCulture() : $culture);
    $languages = $c->getLanguages();

    return isset($languages[$language_iso]) ? $languages[$language_iso] : '';
}
