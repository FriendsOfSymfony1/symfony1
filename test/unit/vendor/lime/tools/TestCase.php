<?php

class TestCase
{
    private $test;

    public function __construct()
    {
        $this->test = new lime_test();
    }

    public function run(): void
    {
        foreach ($this->findAllTests() as $methodName) {
            $this->test->diag($methodName);

            $this->{$methodName}();
        }
    }

    private function findAllTests(): iterable
    {
        foreach (get_class_methods($this) as $methodName) {
            if (0 === strpos($methodName, 'test')) {
                yield $methodName;
            }
        }
    }

    protected function info($message): void
    {
        $this->test->info($message);
    }

    protected function is($expected, $actual, $message = ''): void
    {
        $this->test->is($expected, $actual, $message);
    }
}
