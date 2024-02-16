<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../fixtures/myComponent.php';
require_once __DIR__.'/../sfContext.class.php';
require_once __DIR__.'/../sfNoRouting.class.php';
require_once __DIR__.'/../sfEventDispatcherTestCase.php';

/**
 * @internal
 *
 * @coversNothing
 */
class sfComponentTest extends sfEventDispatcherTestCase
{
    private sfContext $context;

    public function setUp(): void
    {
        $this->context = sfContext::getInstance(array(
            'routing' => 'sfNoRouting',
            'request' => 'sfWebRequest',
        ));

        $this->testObject = new myComponent($this->context, 'module', 'action');
        $this->dispatcher = $this->context->getEventDispatcher();
        $this->class = 'component';
    }

    public function testInitialize()
    {
        $component = new myComponent($this->context, 'module', 'action');
        $this->assertSame($this->context, $component->getContext(), '->initialize() takes a sfContext object as its first argument');
        $component->initialize($this->context, 'module', 'action');
        $this->assertSame($this->context, $component->getContext(), '->initialize() takes a sfContext object as its first argument');
    }

    public function testGetContext()
    {
        $component = new myComponent($this->context, 'module', 'action');

        $component->initialize($this->context, 'module', 'action');
        $this->assertSame($this->context, $component->getContext(), '->getContext() returns the current context');
    }

    public function testGetRequest()
    {
        $component = new myComponent($this->context, 'module', 'action');

        $component->initialize($this->context, 'module', 'action');
        $this->assertSame($this->context->getRequest(), $component->getRequest(), '->getRequest() returns the current request');
    }

    public function testGetResponse()
    {
        $component = new myComponent($this->context, 'module', 'action');

        $component->initialize($this->context, 'module', 'action');
        $this->assertSame($this->context->getResponse(), $component->getResponse(), '->getResponse() returns the current response');
    }

    public function testSetter()
    {
        $component = new myComponent($this->context, 'module', 'action');

        $component->foo = array();
        $component->foo[] = 'bar';
        $this->assertSame(array('bar'), $component->foo, '__set() populates component variables');
    }
}
