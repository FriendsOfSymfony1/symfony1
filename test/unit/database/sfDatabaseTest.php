<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

require_once $_test_dir.'/unit/sfContextMock.class.php';

$t = new lime_test(10);

class myDatabase extends sfDatabase
{
    public function connect()
    {
    }

    public function shutdown()
    {
    }
}

$context = sfContext::getInstance();

$database = new myDatabase();
$database->initialize($context);

// parameter holder proxy
require_once $_test_dir.'/unit/sfParameterHolderTest.class.php';
$pht = new sfParameterHolderProxyTest($t);
$pht->launchTests($database, 'parameter');
