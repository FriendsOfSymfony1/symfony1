<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include(__DIR__.'/../../../../../test/bootstrap/unit.php');

include(__DIR__.'/../../../../autoload/sfSimpleAutoload.class.php');
$autoload = sfSimpleAutoload::getInstance(sys_get_temp_dir().DIRECTORY_SEPARATOR.sprintf('sf_autoload_unit_doctrine_%s.data', md5(__FILE__)));
$autoload->addDirectory(realpath(__DIR__.'/../../lib'));
$autoload->register();

$_test_dir = realpath(__DIR__.'/..');