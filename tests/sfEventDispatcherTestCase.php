<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/fixtures/myEventDispatcherTest.php';

/**
 * @internal
 *
 * @coversNothing
 */
class sfEventDispatcherTestCase extends TestCase
{
    protected $testObject;
    protected $dispatcher;
    protected $class;

    public function testExistedMethod()
    {
        $this->dispatcher->connect($this->class.'.method_not_found', array(myEventDispatcherTest::class, 'newMethod'));

        $this->assertSame('ok', $this->testObject->newMethod('ok'), '__call() accepts new methods via sfEventDispatcher');
    }

    public function testNonExistantMethod()
    {
        $this->expectException(sfException::class);

        $this->testObject->nonexistantmethodname();
    }
}
