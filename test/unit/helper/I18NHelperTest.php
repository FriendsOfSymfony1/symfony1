<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../lib/helper/I18NHelper.php');

$t = new lime_test(2);

// format_number_choice()
$t->diag('format_number_choice()');
$t->is(format_number_choice('[1]1|(1,+Inf]%foo%', array('%foo%' => '(1)'), 1), '1', 'format_number_choice() format a string containing plural information');
$t->is(format_number_choice('[1]1|(1,+Inf]%foo%', array('%foo%' => '(1)'), 2), '(1)', 'format_number_choice() format a string containing plural information');
