<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(20);

class ProjectLoader extends sfServiceContainerLoaderArray
{
    public function validate($content)
    {
        return parent::validate($content);
    }
}

$loader = new ProjectLoader(null);

// ->validate()
try {
    $loader->validate(sfYaml::load(__DIR__.'/fixtures/yaml/nonvalid1.yml'));
    $t->fail('->validate() throws an InvalidArgumentException if the loaded definition is not an array');
} catch (InvalidArgumentException $e) {
    $t->pass('->validate() throws an InvalidArgumentException if the loaded definition is not an array');
}

try {
    $loader->validate(sfYaml::load(__DIR__.'/fixtures/yaml/nonvalid2.yml'));
    $t->fail('->validate() throws an InvalidArgumentException if the loaded definition is not a valid array');
} catch (InvalidArgumentException $e) {
    $t->pass('->validate() throws an InvalidArgumentException if the loaded definition is not a valid array');
}

// ->load() # parameters
$t->diag('->load() # parameters');

list($services, $parameters) = $loader->doLoad([]);
$t->is($parameters, [], '->load() return emty parameters array for an empty array definition');

list($services, $parameters) = $loader->doLoad(sfYaml::load(__DIR__.'/fixtures/yaml/services2.yml'));
$t->is($parameters, ['foo' => 'bar', 'values' => [true, false, 0, 1000.3], 'bar' => 'foo', 'foo_bar' => new sfServiceReference('foo_bar')], '->load() converts array keys to lowercase');

// ->load() # services
list($services, $parameters) = $loader->doLoad(sfYaml::load(__DIR__.'/fixtures/yaml/services2.yml'));
$t->is($services, [], '->load() return emty services array for an empty array definition');

$t->diag('->load() # services');
list($services, $parameters) = $loader->doLoad(sfYaml::load(__DIR__.'/fixtures/yaml/services3.yml'));
$t->ok(isset($services['foo']), '->load() parses service elements');
$t->is(get_class($services['foo']), 'sfServiceDefinition', '->load() converts service element to sfServiceDefinition instances');
$t->is($services['foo']->getClass(), 'FooClass', '->load() parses the class attribute');
$t->ok($services['shared']->isShared(), '->load() parses the shared attribute');
$t->ok(!$services['non_shared']->isShared(), '->load() parses the shared attribute');
$t->is($services['constructor']->getConstructor(), 'getInstance', '->load() parses the constructor attribute');
$t->is($services['file']->getFile(), '%path%/foo.php', '->load() parses the file tag');
$t->is($services['arguments']->getArguments(), ['foo', new sfServiceReference('foo'), [true, false]], '->load() parses the argument tags');
$t->is($services['configurator1']->getConfigurator(), 'sc_configure', '->load() parses the configurator tag');
$t->is($services['configurator2']->getConfigurator(), [new sfServiceReference('baz'), 'configure'], '->load() parses the configurator tag');
$t->is($services['configurator3']->getConfigurator(), ['BazClass', 'configureStatic'], '->load() parses the configurator tag');
$t->is($services['method_call1']->getMethodCalls(), [['setBar', []]], '->load() parses the method_call tag');
$t->is($services['method_call2']->getMethodCalls(), [['setBar', ['foo', new sfServiceReference('foo'), [true, false]]]], '->load() parses the method_call tag');
$t->ok(isset($services['alias_for_foo']), '->load() parses aliases');
$t->is($services['alias_for_foo'], 'foo', '->load() parses aliases');
