<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please component the LICENSE
 * file that was distributed with this source code.
 */

require_once(__DIR__.'/../../bootstrap/unit.php');
require_once($_test_dir.'/unit/sfContextMock.class.php');
require_once($_test_dir.'/unit/sfNoRouting.class.php');

$t = new lime_test(3);

class MyService {
  public function execute()
  {
    return "success";
  }
}

class myComponent extends sfComponent
{
  function execute($request, MyService $service = null) {
    return $service->execute();
  }

  function executeNamed($request, MyService $service) {
    return $service->execute();
  }

  function executeFoo($request, $options = "foo") {
    return $options;
  }
}

$context = sfContext::getInstance(array(
  'routing' => 'sfNoRouting',
  'request' => 'sfWebRequest',
));

$context->getServiceContainer()->setService("MyService", new MyService());

$component = new myComponent($context, 'module', 'action');


$resolver = new sfParameterResolver($context->getServiceContainer());
$resolver
  ->setRequest($context->getRequest())
  ->setComponent($component);


$t->diag('execute()');
$t->is($resolver->execute(), "success", 'without arguments executes default ->execute() method and resolves required dependencies');
$t->is($resolver->execute('executeNamed'), "success", 'can execute an arbitrary method and resolves required dependencies');
$t->is($resolver->execute('executeFoo'), "foo", 'uses default value if no type hint is present');
