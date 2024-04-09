<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';

require_once __DIR__.'/../../bootstrap/functional.php';

ob_start();

$_test_dir = realpath(__DIR__.'/../../');

require_once $_test_dir.'/../lib/vendor/lime/lime.php';

sfConfig::set('sf_symfony_lib_dir', realpath($_test_dir.'/../lib'));

// setup cache
$temp = tempnam('/tmp/cache_dir', 'tmp');
unlink($temp);
mkdir($temp);

$plan = 8;
$t = new lime_test($plan);

// initialize the storage
try {
    $storage = new sfCacheSessionStorage();
    $t->fail('->__construct() does not throw an exception when not provided a cache option');
} catch (InvalidArgumentException $e) {
    $t->pass('->__construct() throws an exception when not provided a cache option');
}

$storage = new sfCacheSessionStorage(['cache' => ['class' => 'sfFileCache', 'param' => ['cache_dir' => $temp]]]);
$t->ok($storage instanceof sfStorage, '->__construct() is an instance of sfStorage');

$storage->write('test', 123);

$t->is($storage->read('test'), 123, '->read() can read data that has been written to storage');

// regenerate()
$oldSessionData = 'foo:bar';
$key = md5($oldSessionData);

$storage->write($key, $oldSessionData);
$session_id = session_id();
$storage->regenerate(false);
$t->is($storage->read($key), $oldSessionData, '->regenerate() regenerated the session with a different session id');
$t->isnt(session_id(), $session_id, '->regenerate() regenerated the session with a different session id');

$storage->regenerate(true);
$t->isnt($storage->read($key), $oldSessionData, '->regenerate() regenerated the session with a different session id and destroyed data');
$t->isnt(session_id(), $session_id, '->regenerate() regenerated the session with a different session id');

$storage->remove($key);
$t->is($storage->read($key), null, '->remove() removes data from the storage');

// shutdown the storage
$storage->shutdown();

// clean up cache
sfToolkit::clearDirectory($temp);
rmdir($temp);
