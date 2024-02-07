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

$t = new \lime_test(41);

function get_cache_manager($context)
{
    \myCache::clear();

    return new \myViewCacheManager($context, new \myCache());
}

function get_cache_config($contextual = false)
{
    return [
        'withLayout' => false,
        'lifeTime' => 86400,
        'clientLifeTime' => 86400,
        'contextual' => $contextual,
        'vary' => [],
    ];
}

class myViewCacheManager extends \sfViewCacheManager
{
    public function registerConfiguration($moduleName)
    {
    }
}

class myController extends \sfWebController
{
}

class myRequest
{
    public $getParameters = ['page' => 5, 'sort' => 'asc'];

    public function getHost()
    {
        return 'localhost';
    }

    public function getScriptName()
    {
        return 'index.php';
    }

    public function getHttpHeader($headerName)
    {
        return '/foo#|#/bar/';
    }

    public function getGetParameters()
    {
        return $this->getParameters;
    }
}

class myCache extends \sfCache
{
    public static $cache = [];

    public function initialize($parameters = [])
    {
    }

    public function get($key, $default = null)
    {
        return isset(self::$cache[$key]) ? self::$cache[$key] : $default;
    }

    public function has($key)
    {
        return isset(self::$cache[$key]);
    }

    public function set($key, $data, $lifetime = null)
    {
        self::$cache[$key] = $data;
    }

    public function remove($key)
    {
        unset(self::$cache[$key]);
    }

    public function removePattern($pattern, $delimiter = ':')
    {
        $pattern = '#^'.str_replace('*', '.*', $pattern).'$#';
        foreach (self::$cache as $key => $value) {
            if (preg_match($pattern, $key)) {
                unset(self::$cache[$key]);
            }
        }
    }

    public function clean($mode = \sfCache::ALL)
    {
        self::$cache = [];
    }

    public function getTimeout($key)
    {
        return time() - 60;
    }

    public function getLastModified($key)
    {
        return time() - 600;
    }

    public static function clear()
    {
        self::$cache = [];
    }
}

class myRouting extends \sfPatternRouting
{
    public $currentInternalUri = 'currentModule/currentAction?currentKey=currentValue';

    public function getCurrentInternalUri($with_route_name = false)
    {
        return $this->currentInternalUri;
    }
}

$context = \sfContext::getInstance(['controller' => 'myController', 'routing' => 'myRouting', 'request' => 'myRequest']);

$r = $context->routing;
$r->connect('default', new \sfRoute('/:module/:action/*'));

// ->initialize()
$t->diag('->initialize()');
$m = new \myViewCacheManager($context, $cache = new \myCache());
$t->is($m->getCache(), $cache, '->initialize() takes a sfCache object as its second argument');

// ->generateCacheKey()
$t->diag('->generateCacheKey');
$t->is($m->generateCacheKey('mymodule/myaction'), '/localhost/all/mymodule/myaction', '->generateCacheKey() creates a simple cache key from an internal URI');
$t->is($m->generateCacheKey('mymodule/myaction', 'foo'), '/foo/all/mymodule/myaction', '->generateCacheKey() can take a hostName as second parameter');
$t->is($m->generateCacheKey('mymodule/myaction', null, 'bar'), '/localhost/bar/mymodule/myaction', '->generateCacheKey() can take a serialized set of vary headers as third parameter');

$t->is($m->generateCacheKey('mymodule/myaction?key1=value1&key2=value2'), '/localhost/all/mymodule/myaction/key1/value1/key2/value2', '->generateCacheKey() includes request parameters as key/value pairs');
$t->is($m->generateCacheKey('mymodule/myaction?akey=value1&ckey=value2&bkey=value3'), '/localhost/all/mymodule/myaction/akey/value1/bkey/value3/ckey/value2', '->generateCacheKey() reorders request parameters alphabetically');

try {
    $m->generateCacheKey('@rule?key=value');
    $t->fail('->generateCacheKey() throws an sfException when passed an internal URI with a rule');
} catch (\sfException $e) {
    $t->pass('->generateCacheKey() throws an sfException when passed an internal URI with a rule');
}

try {
    $m->generateCacheKey('@sf_cache_partial?module=mymodule&action=myaction');
    $t->pass('->generateCacheKey() does not throw an sfException when passed an internal URI with a @sf_cache_partial rule');
} catch (\sfException $e) {
    $t->fail('->generateCacheKey() does not throw an sfException when passed an internal URI with a @sf_cache_partial rule');
}

try {
    $m->generateCacheKey('@sf_cache_partial?key=value');
    $t->fail('->generateCacheKey() throws an sfException when passed an internal URI with a @sf_cache_partial rule with no module or action param');
} catch (\sfException $e) {
    $t->pass('->generateCacheKey() throws an sfException when passed an internal URI with a @sf_cache_partial rule with no module or action param');
}

$t->is($m->generateCacheKey('@sf_cache_partial?module=foo&action=bar&sf_cache_key=value'), '/localhost/all/sf_cache_partial/foo/bar/sf_cache_key/value', '->generateCacheKey() can deal with internal URIs to partials');

$m = get_cache_manager($context);
$m->addCache('foo', 'bar', get_cache_config(true));
$t->is($m->generateCacheKey('@sf_cache_partial?module=foo&action=bar&sf_cache_key=value'), '/localhost/all/currentModule/currentAction/currentKey/currentValue/foo/bar/value', '->generateCacheKey() can deal with internal URIs to contextual partials');

$t->is($m->generateCacheKey('@sf_cache_partial?module=foo&action=bar&sf_cache_key=value', null, null, 'baz'), '/localhost/all/baz/foo/bar/value', '->generateCacheKey() can take a prefix for contextual partials as fourth parameter');

$m = get_cache_manager($context);
$m->addCache('module', 'action', ['vary' => ['myheader', 'secondheader']]);
$t->is($m->generateCacheKey('module/action'), '/localhost/myheader-_foo_bar_-secondheader-_foo_bar_/module/action', '->generateCacheKey() creates a directory friendly vary cache key');

// ->generateNamespace()
$t->diag('->generateNamespace()');
$m = get_cache_manager($context);

// ->addCache()
$t->diag('->addCache()');
$m = get_cache_manager($context);
$m->set('test', 'module/action');
$t->is($m->has('module/action'), false, '->addCache() register a cache configuration for an action');

$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action');
$t->is($m->get('module/action'), 'test', '->addCache() register a cache configuration for an action');

// ->set()
$t->diag('->set()');
$m = get_cache_manager($context);
$t->is($m->set('test', 'module/action'), false, '->set() returns false if the action is not cacheable');
$m->addCache('module', 'action', get_cache_config());
$t->is($m->set('test', 'module/action'), true, '->set() returns true if the action is cacheable');

$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action');
$t->is($m->get('module/action'), 'test', '->set() stores the first parameter in a key computed from the second parameter');

$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action?key1=value1');
$t->is($m->get('module/action?key1=value1'), 'test', '->set() works with URIs with parameters');
$t->is($m->get('module/action?key2=value2'), null, '->set() stores a different version for each set of parameters');
$t->is($m->get('module/action'), null, '->set() stores a different version for each set of parameters');

$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config());
$m->set('test', '@sf_cache_partial?module=module&action=action');
$t->is($m->get('@sf_cache_partial?module=module&action=action'), 'test', '->set() accepts keys to partials');

$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config(true));
$m->set('test', '@sf_cache_partial?module=module&action=action');
$t->is($m->get('@sf_cache_partial?module=module&action=action'), 'test', '->set() accepts keys to contextual partials');

// ->get()
$t->diag('->get()');
$m = get_cache_manager($context);
$t->is($m->get('module/action'), null, '->get() returns null if the action is not cacheable');
$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action');
$t->is($m->get('module/action'), 'test', '->get() returns the saved content if the action is cacheable');

// ->has()
$t->diag('->has()');
$m = get_cache_manager($context);
$t->is($m->has('module/action'), false, '->has() returns false if the action is not cacheable');
$m->addCache('module', 'action', get_cache_config());
$t->is($m->has('module/action'), false, '->has() returns the cache does not exist for the action');
$m->set('test', 'module/action');
$t->is($m->has('module/action'), true, '->get() returns true if the action is in cache');

// ->remove()
$t->diag('->remove()');
$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action');
$m->remove('module/action');
$t->is($m->has('module/action'), false, '->remove() removes cache content for an action');

$m->set('test', 'module/action?key1=value1');
$m->set('test', 'module/action?key2=value2');
$m->remove('module/action?key1=value1');
$t->is($m->has('module/action?key1=value1'), false, '->remove() removes accepts an internal URI as first parameter');
$t->is($m->has('module/action?key2=value2'), true, '->remove() does not remove cache content for keys not matching the internal URI');

$m = get_cache_manager($context);
$m->addCache('module', 'action', get_cache_config());
$m->set('test', 'module/action?key1=value1');
$m->set('test', 'module/action?key1=value2');
$m->set('test', 'module/action?key2=value1');
$m->remove('module/action?key1=*');
$t->is($m->has('module/action?key1=value1'), false, '->remove() accepts wildcards in URIs and then removes all keys matching the pattern');
$t->is($m->has('module/action?key1=value2'), false, '->remove() accepts wildcards in URIs and then removes all keys matching the pattern');
$t->is($m->has('module/action?key2=value1'), true, '->remove() accepts wildcards in URIs and lets keys not matching the pattern unchanged');

$t->diag('Cache key generation options');
$m = new \myViewCacheManager($context, $cache = new \myCache(), ['cache_key_use_vary_headers' => false]);
$t->is($m->generateCacheKey('mymodule/myaction'), '/localhost/mymodule/myaction', '->generateCacheKey() uses "cache_key_use_vary_headers" option to know if vary headers changes cache key.');

$m = new \myViewCacheManager($context, $cache = new \myCache(), ['cache_key_use_host_name' => false]);
$t->is($m->generateCacheKey('mymodule/myaction'), '/all/mymodule/myaction', '->generateCacheKey() uses "cache_key_use_host_name" option to know if vary headers changes cache key.');

$m = new \myViewCacheManager($context, $cache = new \myCache(), ['cache_key_use_host_name' => false, 'cache_key_use_vary_headers' => false]);
$t->is($m->generateCacheKey('mymodule/myaction'), '/mymodule/myaction', '->generateCacheKey() allows the use of both "cache_key_use_host_name" and "cache_key_use_vary_headers" options.');

$m = new \myViewCacheManager($context, new \myCache());
$t->is($m->generateCacheKey('mymodule/myaction?foo=../_bar'), '/localhost/all/mymodule/myaction/foo/_../__bar', '->generateCacheKey() prevents directory traversal');
$t->is($m->generateCacheKey('mymodule/myaction?foo=..\\_bar'), '/localhost/all/mymodule/myaction/foo/_..\\__bar', '->generateCacheKey() prevents directory traversal');

// ->getCurrentCacheKey()
$t->diag('->getCurrentCacheKey()');
$m = get_cache_manager($context);
$t->is($m->getCurrentCacheKey(), 'currentModule/currentAction?currentKey=currentValue&page=5&sort=asc', '->getCurrentCacheKey() appends GET parameters to an existing query string');
$context->getRouting()->currentInternalUri = 'currentModule/currentAction';
$t->is($m->getCurrentCacheKey(), 'currentModule/currentAction?page=5&sort=asc', '->getCurrentCacheKey() adds a query string of GET parameters if none is there');
