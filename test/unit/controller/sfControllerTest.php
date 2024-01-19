<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

require_once $_test_dir.'/unit/sfContextMock.class.php';

$t = new \lime_test(2);

class myController extends \sfController
{
    public function execute()
    {
    }
}

$context = \sfContext::getInstance();

$controller = new \myController($context);

// new methods via sfEventDispatcher
require_once $_test_dir.'/unit/sfEventDispatcherTest.class.php';
$dispatcherTest = new \sfEventDispatcherTest($t);
$dispatcherTest->launchTests($context->getEventDispatcher(), $controller, 'controller');
