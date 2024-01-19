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

$t = new \lime_test(32);

$_SERVER['session_id'] = 'test';

$dispatcher = new \sfEventDispatcher();
$sessionPath = sys_get_temp_dir().'/sessions_'.rand(11111, 99999);
$storage = new \sfSessionTestStorage(['session_path' => $sessionPath]);

$user = new \sfUser($dispatcher, $storage);

// ->initialize()
$t->diag('->initialize()');
$t->is($user->getCulture(), 'en', '->initialize() sets the culture to "en" by default');

$user->setCulture(null);
$user->initialize($dispatcher, $storage, ['default_culture' => 'de']);

user_flush($dispatcher, $user, $storage);

$t->is($user->getCulture(), 'de', '->initialize() sets the culture to the value of default_culture if available');

user_flush($dispatcher, $user, $storage);
$t->is($user->getCulture(), 'de', '->initialize() reads the culture from the session data if available');

$userBis = new \sfUser($dispatcher, $storage);
$t->is($userBis->getCulture(), 'de', '->initialize() serializes the culture to the session data');

// ->setCulture() ->getCulture()
$t->diag('->setCulture() ->getCulture()');
$user->setCulture('fr');
$t->is($user->getCulture(), 'fr', '->setCulture() changes the current user culture');

// ->setFlash() ->getFlash() ->hasFlash()
$t->diag('->setFlash() ->getFlash() ->hasFlash()');
$user->initialize($dispatcher, $storage, ['use_flash' => true]);
$user->setFlash('foo', 'bar');
$t->is($user->getFlash('foo'), 'bar', '->setFlash() sets a flash variable');
$t->is($user->hasFlash('foo'), true, '->hasFlash() returns true if the flash variable exists');
user_flush($dispatcher, $user, $storage, ['use_flash' => true]);

$userBis = new \sfUser($dispatcher, $storage, ['use_flash' => true]);
$t->is($userBis->getFlash('foo'), 'bar', '->getFlash() returns a flash previously set');
$t->is($userBis->hasFlash('foo'), true, '->hasFlash() returns true if the flash variable exists');
user_flush($dispatcher, $user, $storage, ['use_flash' => true]);

$userBis = new \sfUser($dispatcher, $storage, ['use_flash' => true]);
$t->is($userBis->getFlash('foo'), null, 'Flashes are automatically removed after the next request');
$t->is($userBis->hasFlash('foo'), false, '->hasFlash() returns true if the flash variable exists');

// array access for user attributes
$user->setAttribute('foo', 'foo');

$t->diag('Array access for user attributes');
$t->is(isset($user['foo']), true, '->offsetExists() returns true if user attribute exists');
$t->is(isset($user['foo2']), false, '->offsetExists() returns false if user attribute does not exist');
$t->is($user['foo3'], false, '->offsetGet() returns false if attribute does not exist');
$t->is($user['foo'], 'foo', '->offsetGet() returns attribute by name');

$user['foo2'] = 'foo2';
$t->is($user['foo2'], 'foo2', '->offsetSet() sets attribute by name');

unset($user['foo2']);
$t->is(isset($user['foo2']), false, '->offsetUnset() unsets attribute by name');

$user = new \sfUser($dispatcher, $storage);

// attribute holder proxy
require_once $_test_dir.'/unit/sfParameterHolderTest.class.php';
$pht = new \sfParameterHolderProxyTest($t);
$pht->launchTests($user, 'attribute');

// new methods via sfEventDispatcher
require_once $_test_dir.'/unit/sfEventDispatcherTest.class.php';
$dispatcherTest = new \sfEventDispatcherTest($t);
$dispatcherTest->launchTests($dispatcher, $user, 'user');

$storage->clear();

function user_flush($dispatcher, $user, $storage, $options = [])
{
    $user->shutdown();
    $user->initialize($dispatcher, $storage, $options);
    $parameters = $storage->getOptions();
    $storage->shutdown();
    $storage->initialize($parameters);
}

\sfToolkit::clearDirectory($sessionPath);
