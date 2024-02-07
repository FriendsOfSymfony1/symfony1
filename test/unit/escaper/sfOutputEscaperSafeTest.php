<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../lib/vendor/lime/lime.php';

require_once __DIR__.'/../../../lib/helper/EscapingHelper.php';

require_once __DIR__.'/../../../lib/escaper/sfOutputEscaperSafe.class.php';

$t = new \lime_test(13);

// ->getValue()
$t->diag('->getValue()');
$safe = new \sfOutputEscaperSafe('foo');
$t->is($safe->getValue(), 'foo', '->getValue() returns the embedded value');

// ->__set() ->__get()
$t->diag('->__set() ->__get()');

class TestClass1
{
    public $foo = 'bar';
}

$safe = new \sfOutputEscaperSafe(new \TestClass1());

$t->is($safe->foo, 'bar', '->__get() returns the object parameter');
$safe->foo = 'baz';
$t->is($safe->foo, 'baz', '->__set() sets the object parameter');

// ->__call()
$t->diag('->__call()');

class TestClass2
{
    public function doSomething()
    {
        return 'ok';
    }
}

$safe = new \sfOutputEscaperSafe(new \TestClass2());
$t->is($safe->doSomething(), 'ok', '->__call() invokes the embedded method');

// ->__isset() ->__unset()
$t->diag('->__isset() ->__unset()');

class TestClass3
{
    public $boolValue = true;
    public $nullValue;
}

$safe = new \sfOutputEscaperSafe(new \TestClass3());

$t->is(isset($safe->boolValue), true, '->__isset() returns true if the property is not null');
$t->is(isset($safe->nullValue), false, '->__isset() returns false if the property is null');
$t->is(isset($safe->undefinedValue), false, '->__isset() returns false if the property does not exist');

unset($safe->boolValue);
$t->is(isset($safe->boolValue), false, '->__unset() unsets the embedded property');

// Iterator
$t->diag('Iterator');

$input = ['one' => 1, 'two' => 2, 'three' => 3, 'children' => [1, 2, 3]];
$output = [];

$safe = new \sfOutputEscaperSafe($input);
foreach ($safe as $key => $value) {
    $output[$key] = $value;
}
$t->is_deeply($output, $input, '"Iterator" implementation imitates an array');

// ArrayAccess
$t->diag('ArrayAccess');

$safe = new \sfOutputEscaperSafe(['foo' => 'bar']);

$t->is($safe['foo'], 'bar', '"ArrayAccess" implementation returns a value from the embedded array');
$safe['foo'] = 'baz';
$t->is($safe['foo'], 'baz', '"ArrayAccess" implementation sets a value on the embedded array');
$t->is(isset($safe['foo']), true, '"ArrayAccess" checks if a value is set on the embedded array');
unset($safe['foo']);
$t->is(isset($safe['foo']), false, '"ArrayAccess" unsets a value on the embedded array');
