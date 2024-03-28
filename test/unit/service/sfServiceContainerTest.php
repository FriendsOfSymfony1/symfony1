<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(27);

// __construct()
$t->diag('__construct()');
$sc = new sfServiceContainer();
$t->is(spl_object_hash($sc->getService('service_container')), spl_object_hash($sc), '__construct() automatically registers itself as a service');

$sc = new sfServiceContainer(['foo' => 'bar']);
$t->is($sc->getParameters(), ['foo' => 'bar'], '__construct() takes an array of parameters as its first argument');

// ->setParameters() ->getParameters()
$t->diag('->setParameters() ->getParameters()');

$sc = new sfServiceContainer();
$t->is($sc->getParameters(), [], '->getParameters() returns an empty array if no parameter has been defined');

$sc->setParameters(['foo' => 'bar']);
$t->is($sc->getParameters(), ['foo' => 'bar'], '->setParameters() sets the parameters');

$sc->setParameters(['bar' => 'foo']);
$t->is($sc->getParameters(), ['bar' => 'foo'], '->setParameters() overrides the previous defined parameters');

$sc->setParameters(['Bar' => 'foo']);
$t->is($sc->getParameters(), ['bar' => 'foo'], '->setParameters() converts the key to lowercase');

// ->setParameter() ->getParameter()
$t->diag('->setParameter() ->getParameter() ');

$sc = new sfServiceContainer(['foo' => 'bar']);
$sc->setParameter('bar', 'foo');
$t->is($sc->getParameter('bar'), 'foo', '->setParameter() sets the value of a new parameter');
$t->is($sc->getParameter('bar'), 'foo', '->getParameter() gets the value of a parameter');

$sc->setParameter('foo', 'baz');
$t->is($sc->getParameter('foo'), 'baz', '->setParameter() overrides previously set parameter');

$sc->setParameter('Foo', 'baz1');
$t->is($sc->getParameter('foo'), 'baz1', '->setParameter() converts the key to lowercase');
$t->is($sc->getParameter('FOO'), 'baz1', '->getParameter() converts the key to lowercase');

try {
    $sc->getParameter('baba');
    $t->fail('->getParameter() thrown an InvalidArgumentException if the key does not exist');
} catch (InvalidArgumentException $e) {
    $t->pass('->getParameter() thrown an InvalidArgumentException if the key does not exist');
}

// ->hasParameter()
$t->diag('->hasParameter()');
$sc = new sfServiceContainer(['foo' => 'bar']);
$t->ok($sc->hasParameter('foo'), '->hasParameter() returns true if a parameter is defined');
$t->ok($sc->hasParameter('Foo'), '->hasParameter() converts the key to lowercase');
$t->ok(!$sc->hasParameter('bar'), '->hasParameter() returns false if a parameter is not defined');

// ->addParameters()
$t->diag('->addParameters()');
$sc = new sfServiceContainer(['foo' => 'bar']);
$sc->addParameters(['bar' => 'foo']);
$t->is($sc->getParameters(), ['foo' => 'bar', 'bar' => 'foo'], '->addParameters() adds parameters to the existing ones');
$sc->addParameters(['Bar' => 'fooz']);
$t->is($sc->getParameters(), ['foo' => 'bar', 'bar' => 'fooz'], '->addParameters() converts keys to lowercase');

// ->setService() ->hasService() ->getService()
$t->diag('->setService() ->hasService() ->getService()');
$sc = new sfServiceContainer();
$sc->setService('foo', $obj = new stdClass());
$t->is(spl_object_hash($sc->getService('foo')), spl_object_hash($obj), '->setService() registers a service under a key name');

$sc->foo1 = $obj1 = new stdClass();
$t->ok($sc->hasService('foo'), '->hasService() returns true if the service is defined');
$t->ok(!$sc->hasService('bar'), '->hasService() returns false if the service is not defined');

// ->getServiceIds()
$t->diag('->getServiceIds()');
$sc = new sfServiceContainer();
$sc->setService('foo', $obj = new stdClass());
$sc->setService('bar', $obj = new stdClass());
$t->is($sc->getServiceIds(), ['service_container', 'foo', 'bar'], '->getServiceIds() returns all defined service ids');

class ProjectServiceContainer extends sfServiceContainer
{
    public $__bar;
    public $__foo_bar;
    public $__foo_baz;

    public function __construct()
    {
        parent::__construct();

        $this->__bar = new stdClass();
        $this->__foo_bar = new stdClass();
        $this->__foo_baz = new stdClass();
    }

    protected function getBarService()
    {
        return $this->__bar;
    }

    protected function getFooBarService()
    {
        return $this->__foo_bar;
    }

    protected function getFoo_BazService()
    {
        return $this->__foo_baz;
    }
}

$sc = new ProjectServiceContainer();
$t->is(spl_object_hash($sc->getService('bar')), spl_object_hash($sc->__bar), '->getService() looks for a getXXXService() method');
$t->ok($sc->hasService('bar'), '->hasService() returns true if the service has been defined as a getXXXService() method');

$sc->setService('bar', $bar = new stdClass());
$t->is(spl_object_hash($sc->getService('bar')), spl_object_hash($bar), '->getService() prefers to return a service defined with setService() than one defined with a getXXXService() method');

try {
    $sc->getService('baba');
    $t->fail('->getService() thrown an InvalidArgumentException if the service does not exist');
} catch (InvalidArgumentException $e) {
    $t->pass('->getService() thrown an InvalidArgumentException if the service does not exist');
}

$t->is(spl_object_hash($sc->getService('foo_bar')), spl_object_hash($sc->__foo_bar), '->getService() camelizes the service id when looking for a method');
$t->is(spl_object_hash($sc->getService('foo.baz')), spl_object_hash($sc->__foo_baz), '->getService() camelizes the service id when looking for a method');
