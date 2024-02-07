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

class myResponse extends \sfResponse
{
    public function __serialize()
    {
    }

    public function __unserialize($data)
    {
    }

    public function serialize()
    {
    }

    public function unserialize($serialized)
    {
    }
}

class fakeResponse
{
}

$t = new \lime_test(8);

$dispatcher = new \sfEventDispatcher();

// ->initialize()
$t->diag('->initialize()');
$response = new \myResponse($dispatcher, ['foo' => 'bar']);
$options = $response->getOptions();
$t->is($options['foo'], 'bar', '->initialize() takes an array of options as its second argument');
$t->is($options['logging'], false, '->getOptions() returns options for response instance');

// ->getContent() ->setContent()
$t->diag('->getContent() ->setContent()');
$t->is($response->getContent(), null, '->getContent() returns the current response content which is null by default');
$response->setContent('test');
$t->is($response->getContent(), 'test', '->setContent() sets the response content');

// ->sendContent()
$t->diag('->sendContent()');
ob_start();
$response->sendContent();
$content = ob_get_clean();
$t->is($content, 'test', '->sendContent() output the current response content');

// ->serialize() ->unserialize()
$t->diag('->serialize() ->unserialize()');
$t->ok(new \myResponse($dispatcher) instanceof \Serializable, 'sfResponse implements the Serializable interface');

// new methods via sfEventDispatcher
require_once $_test_dir.'/unit/sfEventDispatcherTest.class.php';
$dispatcherTest = new \sfEventDispatcherTest($t);
$dispatcherTest->launchTests($dispatcher, $response, 'response');
