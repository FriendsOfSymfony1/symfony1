<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(21);

// __construct()
$t->diag('__construct()');

$def = new sfServiceDefinition('stdClass');
$t->is($def->getClass(), 'stdClass', '__construct() takes the class name as its first argument');

$def = new sfServiceDefinition('stdClass', ['foo']);
$t->is($def->getArguments(), ['foo'], '__construct() takes an optional array of arguments as its second argument');

// ->setConstructor() ->getConstructor()
$t->diag('->setConstructor() ->getConstructor()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setConstructor('foo')), spl_object_hash($def), '->setConstructor() implements a fluent interface');
$t->is($def->getConstructor(), 'foo', '->getConstructor() returns the constructor name');

// ->setClass() ->getClass()
$t->diag('->setClass() ->getClass()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setClass('foo')), spl_object_hash($def), '->setClass() implements a fluent interface');
$t->is($def->getClass(), 'foo', '->getClass() returns the class name');

// ->setArguments() ->getArguments() ->addArgument()
$t->diag('->setArguments() ->getArguments() ->addArgument()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setArguments(['foo'])), spl_object_hash($def), '->setArguments() implements a fluent interface');
$t->is($def->getArguments(), ['foo'], '->getArguments() returns the arguments');
$t->is(spl_object_hash($def->addArgument('bar')), spl_object_hash($def), '->addArgument() implements a fluent interface');
$t->is($def->getArguments(), ['foo', 'bar'], '->addArgument() adds an argument');

// ->setMethodCalls() ->getMethodCalls() ->addMethodCall()
$t->diag('->setMethodCalls() ->getMethodCalls() ->addMethodCall()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setMethodCalls([['foo', ['foo']]])), spl_object_hash($def), '->setMethodCalls() implements a fluent interface');
$t->is($def->getMethodCalls(), [['foo', ['foo']]], '->getMethodCalls() returns the methods to call');
$t->is(spl_object_hash($def->addMethodCall('bar', ['bar'])), spl_object_hash($def), '->addMethodCall() implements a fluent interface');
$t->is($def->getMethodCalls(), [['foo', ['foo']], ['bar', ['bar']]], '->addMethodCall() adds a method to call');

// ->setFile() ->getFile()
$t->diag('->setFile() ->getFile()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setFile('foo')), spl_object_hash($def), '->setFile() implements a fluent interface');
$t->is($def->getFile(), 'foo', '->getFile() returns the file to include');

// ->setShared() ->isShared()
$t->diag('->setShared() ->isShared()');
$def = new sfServiceDefinition('stdClass');
$t->is($def->isShared(), true, '->isShared() returns true by default');
$t->is(spl_object_hash($def->setShared(false)), spl_object_hash($def), '->setShared() implements a fluent interface');
$t->is($def->isShared(), false, '->isShared() returns false if the instance must not be shared');

// ->setConfigurator() ->getConfigurator()
$t->diag('->setConfigurator() ->getConfigurator()');
$def = new sfServiceDefinition('stdClass');
$t->is(spl_object_hash($def->setConfigurator('foo')), spl_object_hash($def), '->setConfigurator() implements a fluent interface');
$t->is($def->getConfigurator(), 'foo', '->getConfigurator() returns the configurator');
