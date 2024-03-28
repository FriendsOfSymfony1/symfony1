<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(16);

abstract class BaseTestTask extends sfTask
{
    public $lastArguments = [];
    public $lastOptions = [];

    public function __construct()
    {
        // lazy constructor
        parent::__construct(new sfEventDispatcher(), new sfFormatter());
    }

    protected function execute($arguments = [], $options = [])
    {
        $this->lastArguments = $arguments;
        $this->lastOptions = $options;
    }
}

// ->run()
$t->diag('->run()');

class ArgumentsTest1Task extends BaseTestTask
{
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('foo', sfCommandArgument::REQUIRED),
            new sfCommandArgument('bar', sfCommandArgument::OPTIONAL),
        ]);
    }
}

$task = new ArgumentsTest1Task();
$task->run(['FOO']);
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => null], '->run() accepts an indexed array of arguments');

$task->run(['foo' => 'FOO']);
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => null], '->run() accepts an associative array of arguments');

$task->run(['bar' => 'BAR', 'foo' => 'FOO']);
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => 'BAR'], '->run() accepts an unordered associative array of arguments');

$task->run('FOO BAR');
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => 'BAR'], '->run() accepts a string of arguments');

$task->run(['foo' => 'FOO', 'bar' => null]);
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => null], '->run() accepts an associative array of arguments when optional arguments are passed as null');

$task->run(['bar' => null, 'foo' => 'FOO']);
$t->is_deeply($task->lastArguments, ['foo' => 'FOO', 'bar' => null], '->run() accepts an unordered associative array of arguments when optional arguments are passed as null');

class ArgumentsTest2Task extends BaseTestTask
{
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('foo', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY),
        ]);
    }
}

$task = new ArgumentsTest2Task();
$task->run(['arg1', 'arg2', 'arg3']);
$t->is_deeply($task->lastArguments, ['foo' => ['arg1', 'arg2', 'arg3']], '->run() accepts an indexed array of an IS_ARRAY argument');

$task->run(['foo' => ['arg1', 'arg2', 'arg3']]);
$t->is_deeply($task->lastArguments, ['foo' => ['arg1', 'arg2', 'arg3']], '->run() accepts an associative array of an IS_ARRAY argument');

class OptionsTest1Task extends BaseTestTask
{
    protected function configure()
    {
        $this->addOptions([
            new sfCommandOption('none', null, sfCommandOption::PARAMETER_NONE),
            new sfCommandOption('required', null, sfCommandOption::PARAMETER_REQUIRED),
            new sfCommandOption('optional', null, sfCommandOption::PARAMETER_OPTIONAL),
            new sfCommandOption('array', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY),
        ]);
    }
}

$task = new OptionsTest1Task();
$task->run();
$t->is_deeply($task->lastOptions, ['none' => false, 'required' => null, 'optional' => null, 'array' => []], '->run() sets empty option values');

$task->run([], ['--none', '--required=TEST1', '--array=one', '--array=two', '--array=three']);
$t->is_deeply($task->lastOptions, ['none' => true, 'required' => 'TEST1', 'optional' => null, 'array' => ['one', 'two', 'three']], '->run() accepts an indexed array of option values');

$task->run([], ['none', 'required=TEST1', 'array=one', 'array=two', 'array=three']);
$t->is_deeply($task->lastOptions, ['none' => true, 'required' => 'TEST1', 'optional' => null, 'array' => ['one', 'two', 'three']], '->run() accepts an indexed array of unflagged option values');

$task->run([], ['none' => false, 'required' => 'TEST1', 'array' => ['one', 'two', 'three']]);
$t->is_deeply($task->lastOptions, ['none' => false, 'required' => 'TEST1', 'optional' => null, 'array' => ['one', 'two', 'three']], '->run() accepts an associative array of option values');

$task->run([], ['optional' => null, 'array' => []]);
$t->is_deeply($task->lastOptions, ['none' => false, 'required' => null, 'optional' => null, 'array' => []], '->run() accepts an associative array of options when optional values are passed as empty');

$task->run('--none --required=TEST1 --array=one --array=two --array=three');
$t->is_deeply($task->lastOptions, ['none' => true, 'required' => 'TEST1', 'optional' => null, 'array' => ['one', 'two', 'three']], '->run() accepts a string of options');

$task->run([], ['array' => 'one']);
$t->is_deeply($task->lastOptions, ['none' => false, 'required' => null, 'optional' => null, 'array' => ['one']], '->run() accepts an associative array of options with a scalar array option value');

// ->getDetailedDescription()
$t->diag('->getDetailedDescription()');

class DetailedDescriptionTestTask extends BaseTestTask
{
    protected function configure()
    {
        $this->detailedDescription = <<<'EOF'
The [detailedDescription|INFO] formats special string like [...|COMMENT] or [--xml|COMMENT]
EOF;
    }
}

$task = new DetailedDescriptionTestTask();
$t->is($task->getDetailedDescription(), 'The detailedDescription formats special string like ... or --xml', '->getDetailedDescription() formats special string');
