<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class sfParameterHolderProxyTestCase extends TestCase
{
    protected string $methodName = 'parameter';
    protected object $object;

    public function testMethodHolderProxy()
    {
        $hasMethod = 'has'.ucfirst($this->methodName);
        $getMethod = 'get'.ucfirst($this->methodName);
        $setMethod = 'set'.ucfirst($this->methodName);
        $holderMethod = 'get'.ucfirst($this->methodName).'Holder';

        $namespaced = $this->object->{$holderMethod}() instanceof sfNamespacedParameterHolder ? true : false;

        $this->assertInstanceOf($namespaced ? 'sfNamespacedParameterHolder' : 'sfParameterHolder', $this->object->{$holderMethod}(), "->{$holderMethod}() returns a parameter holder instance");
        $this->assertSame(false, $this->object->{$hasMethod}('foo'), "->{$hasMethod}() returns false if the {$this->methodName} does not exist");
        $this->assertSame('default', $this->object->{$getMethod}('foo', 'default'), "->{$getMethod}() returns the default value if {$this->methodName} does not exist");
        $this->object->{$setMethod}('foo', 'bar');
        $this->assertSame(true, $this->object->{$hasMethod}('foo'), "->{$hasMethod}() returns true if the {$this->methodName} exists");
        $this->assertSame($this->object->{$holderMethod}()->has('foo'), $this->object->{$hasMethod}('foo'), "->{$hasMethod}() is a proxy method");
        $this->assertSame('bar', $this->object->{$getMethod}('foo'), "->{$getMethod}() returns the value of the {$this->methodName}");
        $this->assertSame($this->object->{$holderMethod}()->get('foo'), $this->object->{$getMethod}('foo'), "->{$getMethod}() is a proxy method");
        $this->assertSame('bar', $this->object->{$getMethod}('foo', 'default'), "->{$getMethod}() does not return the default value if the {$this->methodName} exists");

        if ($namespaced) {
            $this->object->{$setMethod}('foo1', 'bar1', 'mynamespace');
            $this->assertSame(false, $this->object->{$hasMethod}('foo1'), "->{$hasMethod}() takes a namespace as its second parameter");
            $this->assertSame(true, $this->object->{$hasMethod}('foo1', 'mynamespace'), "->{$hasMethod}() takes a namespace as its second parameter");
            $this->assertSame('bar1', $this->object->{$getMethod}('foo1', 'default', 'mynamespace'), "->{$getMethod}() takes a namespace as its third parameter");
        }

        $this->object->{$setMethod}('foo2', 'bar2');
        $this->object->{$holderMethod}()->set('foo3', 'bar3');
        $this->assertSame($this->object->{$holderMethod}()->get('foo2'), $this->object->{$getMethod}('foo2'), "->{$setMethod}() is a proxy method");
        $this->assertSame($this->object->{$holderMethod}()->get('foo3'), $this->object->{$getMethod}('foo3'), "->{$setMethod}() is a proxy method");
    }
}
