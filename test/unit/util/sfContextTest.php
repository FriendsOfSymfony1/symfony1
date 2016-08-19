<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(__DIR__.'/../../bootstrap/unit.php');

$t = new lime_test(29);

class myContext extends sfContext
{
  public function initialize(sfApplicationConfiguration $configuration)
  {
  }
}

/*
// unit testing sfContext requires mock configuration / app
// this test requires the functional project configurations

class ProjectConfiguration extends sfProjectConfiguration
{
}

class frontendConfiguration extends sfApplicationConfiguration
{
}
*/

// use functional project configruration
require_once realpath(__DIR__.'/../../functional/fixtures/config/ProjectConfiguration.class.php');

$frontend_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true));
$frontend_context_prod = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false), 'frontend_prod');
$i18n_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('i18n', 'test', true));
$cache_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('cache', 'test', true));


// ::getInstance()
$t->diag('::getInstance()');
$t->isa_ok($frontend_context, 'sfContext', '::createInstance() takes an application configuration and returns application context instance');
$t->isa_ok(sfContext::getInstance('frontend_prod'), 'sfContext', '::createInstance() creates application name context instance');

$context = sfContext::getInstance('frontend_prod');
$context1 = sfContext::getInstance('i18n');
$context2 = sfContext::getInstance('cache');
$t->is(sfContext::getInstance('i18n'), $context1, '::getInstance() returns the named context if it already exists');

// ::switchTo();
$t->diag('::switchTo()');
sfContext::switchTo('i18n');
$t->is(sfContext::getInstance(), $context1, '::switchTo() changes the default context instance returned by ::getInstance()');
sfContext::switchTo('cache');
$t->is(sfContext::getInstance(), $context2, '::switchTo() changes the default context instance returned by ::getInstance()');

// ->get() ->set() ->has()
$t->diag('->get() ->set() ->has()');
$t->is($context1->has('object'), false, '->has() returns false if no object of the given name exist');
$object = new stdClass();
$context1->set('object', $object, '->set() stores an object in the current context instance');
$t->is($context1->has('object'), true, '->has() returns true if an object is stored for the given name');
$t->is($context1->get('object'), $object, '->get() returns the object associated with the given name');
try
{
  $context1->get('object1');
  $t->fail('->get() throws an sfException if no object is stored for the given name');
}
catch (sfException $e)
{
  $t->pass('->get() throws an sfException if no object is stored for the given name');
}

$context['foo'] = $frontend_context;
$t->diag('Array access for context objects');
$t->is(isset($context['foo']), true, '->offsetExists() returns true if context object exists');
$t->is(isset($context['foo2']), false, '->offsetExists() returns false if context object does not exist');
$t->isa_ok($context['foo'], 'sfContext', '->offsetGet() returns attribute by name');

$context['foo2'] = $i18n_context;
$t->isa_ok($context['foo2'], 'sfContext', '->offsetSet() sets object by name');

unset($context['foo2']);
$t->is(isset($context['foo2']), false, '->offsetUnset() unsets object by name');

$t->diag('->__call()');

$context->setFoo4($i18n_context);
$t->is($context->has('foo4'), true, '->__call() sets context objects by name using setName()');
$t->isa_ok($context->getFoo4(), 'sfContext', '->__call() returns context objects by name using getName()');

try
{
  $context->unknown();
  $t->fail('->__call() throws an sfException if factory / method does not exist');
}
catch (sfException $e)
{
  $t->pass('->__call() throws an sfException if factory / method does not exist');
}

$context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true), 'frontend_dic');
sfContext::switchTo('frontend_dic');

$t->diag('->getServiceContainer() test');
$sc = $context->getServiceContainer();
$className = sfInflector::camelize('frontend_dic') . 'ContextServiceContainer';
$scFile = sfConfig::get('sf_config_cache_dir') . "/{$className}.php";
$t->diag($scFile);
$t->ok(is_file($scFile), '->getServiceContainer() creates a cache file in /cache/frontend/test/config');
$t->ok(class_exists($className), '->getServiceContainer() creates and loads the frontend_testServiceContainer class');
$t->ok($sc instanceof sfServiceContainer, '->getServiceContainer() returns an instance of ContainerInterface');
$t->ok($sc->hasService('my_app_service'), '->getServiceContainer() contains app/config/service.yml services');
$t->ok($sc->hasService('my_project_service'), '->getServiceContainer() contains /config/service.yml services');
$t->ok($sc->hasService('my_plugin_service'), '->getServiceContainer() contains plugin/config/service.yml services');
$t->ok($sc->getParameter('sf_root_dir'), '->getServiceContainer() sfConfig parameters are accessible');
$t->ok($sc->hasParameter('my_app_test_param'), '->getServiceContainer() contains env specific parameters');

sfContext::switchTo('frontend_prod');
$sc = $frontend_context_prod->getServiceContainer();
$t->diag('->getServiceContainer() prod');
$class = ucfirst($frontend_context_prod->getConfiguration()->getApplication()) . 'ContextServiceContainer';
$className = sfInflector::camelize('frontend_prod') . 'ContextServiceContainer';
$scFile = sfConfig::get('sf_config_cache_dir') . "/{$className}.php";
$t->diag($scFile);
$t->ok(is_file($scFile), '->getServiceContainer() creates a cache file in /cache/frontend/prod/config');
$t->ok(class_exists($className), '->getServiceContainer() creates and loads the frontend_prodServiceContainer class');
$t->ok($sc instanceof sfServiceContainer, '->getServiceContainer() returns an instance of frontend_prodServiceContainer');
$t->ok(false === $sc->hasParameter('my_app_test_param'), '->getServiceContainer() does not contain other env specific parameters');
