<?php

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class sfServiceSimpleXMLElement extends SimpleXMLElement
{
  public function getAttributeAsPhp($name)
  {
    return self::phpize($this[$name]);
  }

  public function getArgumentsAsPhp($name)
  {
    $arguments = array();
    foreach ($this->$name as $arg)
    {
      $key = isset($arg['key']) ? (string) $arg['key'] : (!$arguments ? 0 : max(array_keys($arguments)) + 1);

      // parameter keys are case insensitive
      if ('parameter' == $name)
      {
        $key = strtolower($key);
      }

      switch ($arg['type'])
      {
        case 'service':
          $arguments[$key] = new sfServiceReference((string) $arg['id']);
          break;
        case 'collection':
          $arguments[$key] = $arg->getArgumentsAsPhp($name);
          break;
        case 'string':
          $arguments[$key] = (string) $arg;
          break;
        default:
          $arguments[$key] = self::phpize($arg);
      }
    }

    return $arguments;
  }

  static public function phpize($value)
  {
    $value = (string) $value;

    switch (true)
    {
      case 'null' == strtolower($value):
        return null;
      case ctype_digit($value):
        return '0' == $value[0] ? octdec($value) : intval($value);
      case in_array(strtolower($value), array('true', 'on')):
        return true;
      case in_array(strtolower($value), array('false', 'off')):
        return false;
      case is_numeric($value):
        return '0x' == $value[0].$value[1] ? hexdec($value) : floatval($value);
      case preg_match('/^(-|\+)?[0-9,]+(\.[0-9]+)?$/', $value):
        return floatval(str_replace(',', '', $value));
      default:
        return (string) $value;
    }
  }
}
