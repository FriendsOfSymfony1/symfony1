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

$t = new \lime_test(29);

class myContext extends \sfContext
{
    public function initialize(\sfApplicationConfiguration $configuration)
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

$frontend_context = \sfContext::createInstance(\ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true));
$frontend_context_prod = \sfContext::createInstance(\ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false));
$i18n_context = \sfContext::createInstance(\ProjectConfiguration::getApplicationConfiguration('i18n', 'test', true));
$cache_context = \sfContext::createInstance(\ProjectConfiguration::getApplicationConfiguration('cache', 'test', true));

// ::getInstance()
$t->diag('::getInstance()');
$t->isa_ok($frontend_context, 'sfContext', '::createInstance() takes an application configuration and returns application context instance');
$t->isa_ok(\sfContext::getInstance('frontend'), 'sfContext', '::createInstance() creates application name context instance');

$context = \sfContext::getInstance('frontend');
$context1 = \sfContext::getInstance('i18n');
$context2 = \sfContext::getInstance('cache');
$t->is(\sfContext::getInstance('i18n'), $context1, '::getInstance() returns the named context if it already exists');

// ::switchTo();
$t->diag('::switchTo()');
\sfContext::switchTo('i18n');
$t->is(\sfContext::getInstance(), $context1, '::switchTo() changes the default context instance returned by ::getInstance()');
\sfContext::switchTo('cache');
$t->is(\sfContext::getInstance(), $context2, '::switchTo() changes the default context instance returned by ::getInstance()');

// ->get() ->set() ->has()
$t->diag('->get() ->set() ->has()');
$t->is($context1->has('object'), false, '->has() returns false if no object of the given name exist');
$object = new \stdClass();
$context1->set('object', $object, '->set() stores an object in the current context instance');
$t->is($context1->has('object'), true, '->has() returns true if an object is stored for the given name');
$t->is($context1->get('object'), $object, '->get() returns the object associated with the given name');

try {
    $context1->get('object1');
    $t->fail('->get() throws an sfException if no object is stored for the given name');
} catch (\sfException $e) {
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

try {
    $context->unknown();
    $t->fail('->__call() throws an sfException if factory / method does not exist');
} catch (\sfException $e) {
    $t->pass('->__call() throws an sfException if factory / method does not exist');
}

$t->diag('->getServiceContainer() test');
$sc = $frontend_context->getServiceContainer();
$t->ok(file_exists(\sfConfig::get('sf_cache_dir').'/frontend/test/config/config_services.yml.php'), '->getServiceContainer() creates a cache file in /cache/frontend/test/config');
$t->ok(class_exists('frontend_testServiceContainer'), '->getServiceContainer() creates and loads the frontend_testServiceContainer class');
$t->ok($sc instanceof \frontend_testServiceContainer, '->getServiceContainer() returns an instance of frontend_testServiceContainer');
$t->ok($sc->hasService('my_app_service'), '->getServiceContainer() contains app/config/service.yml services');
$t->ok($sc->hasService('my_project_service'), '->getServiceContainer() contains /config/service.yml services');
$t->ok($sc->hasService('my_plugin_service'), '->getServiceContainer() contains plugin/config/service.yml services');
$t->ok($sc->getParameter('sf_root_dir'), '->getServiceContainer() sfConfig parameters are accessibles');
$t->ok($sc->hasParameter('my_app_test_param'), '->getServiceContainer() contains env specifiv parameters');

$t->diag('->getServiceContainer() prod');
$sc = $frontend_context_prod->getServiceContainer();
$t->ok(file_exists(\sfConfig::get('sf_cache_dir').'/frontend/prod/config/config_services.yml.php'), '->getServiceContainer() creates a cache file in /cache/frontend/prod/config');
$t->ok(class_exists('frontend_prodServiceContainer'), '->getServiceContainer() creates and loads the frontend_prodServiceContainer class');
$t->ok($sc instanceof \frontend_prodServiceContainer, '->getServiceContainer() returns an instance of frontend_prodServiceContainer');
$t->ok(false === $sc->hasParameter('my_app_test_param'), '->getServiceContainer() does not contain other env specifiv parameters');
