<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$validV4Ips = array(
  '127.0.0.1',
  '0.0.0.0',
  '255.255.255.255',
);

$invalidV4Ips = array(
  '127.0.0',
  '256.256.256.256',
  '192.168.0.256',
);

$validV6Ips = array(
  '2001:db8::1:0:0:1',
  '2001:0DB8:0:0:1::1',
);

$invalidV6Ips = array(
  '0000:db8:0:0',
);

$t = new lime_test(count($validV4Ips) + count($invalidV4Ips) + count($validV6Ips) + count($invalidV6Ips), new lime_output_color());

$v = new sfValidatorIp();

$t->diag('testing against valid IP');
foreach (array_merge($validV4Ips, $validV6Ips) as $ip)
{
  try
  {
    $v->clean($ip);
    $t->pass(sprintf("%s is considered as valid ip", $ip));
  }
  catch (sfValidatorError $e)
  {
    $t->fail(sprintf("%s is considered as valid ip", $ip));
  }
}

$t->diag('testing against invalid IP');
foreach (array_merge($invalidV4Ips, $invalidV6Ips) as $ip)
{
  try
  {
    $v->clean($ip);
    $t->fail(sprintf("%s is not considered as valid ip", $ip));
  }
  catch (sfValidatorError $e)
  {
    $t->pass(sprintf("%s is not considered as valid ip", $ip));
  }
}
