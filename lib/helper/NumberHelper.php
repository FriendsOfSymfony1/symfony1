<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * NumberHelper.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 *
 * @param mixed|null $culture
 * @param mixed      $number
 */
function format_number($number, $culture = null)
{
    if (null === $number) {
        return null;
    }

    $numberFormat = new sfNumberFormat(_current_language($culture));

    return $numberFormat->format($number);
}

function format_currency($amount, $currency = null, $culture = null)
{
    if (null === $amount) {
        return null;
    }

    $numberFormat = new sfNumberFormat(_current_language($culture));

    return $numberFormat->format($amount, 'c', $currency);
}

function _current_language($culture)
{
    return $culture ?: sfContext::getInstance()->getUser()->getCulture();
}
