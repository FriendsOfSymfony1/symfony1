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
 * @package    symfony
 * @subpackage helper
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */

function format_number($number, $culture = null)
{
  if (null === $number)
  {
    return null;
  }

  $numberFormat = new sfNumberFormat(_current_language($culture));

  return $numberFormat->format($number);
}

function format_currency($amount, $currency = null, $culture = null)
{
  if (null === $amount)
  {
    return null;
  }

  $numberFormat = new sfNumberFormat(_current_language($culture));

  //remove nbsp (sonst erkennt Excel keine Zahl)
  return substr($numberFormat->format($amount, 'c', $currency, 'ISO-8859-15'), 0, -1);
  //return rtrim($numberFormat->format($amount, 'c', $currency, 'ISO-8859-15'), '0\xa0');
}

function _current_language($culture)
{
  return $culture ?: sfContext::getInstance()->getUser()->getCulture();
}
