<?php

require_once __DIR__.'/../../../bootstrap/unit.php';
require_once __DIR__.'/tools/TestCase.php';

/**
 * @internal
 *
 * @coversNothing
 */
class lime_testTest extends TestCase
{
    private function whenExecutePhpFileWillHaveStatusCodeAndOutput($name, $expectedStatusCode, $expectedOutput)
    {
        $this->info($name);

        ob_start();
        $exitCode = (new lime_harness())->executePhpFile(__DIR__.'/fixtures/'.$name.'.php');
        $output = ob_get_clean();

        $this->is($exitCode, $expectedStatusCode, 'with test '.$name.' will exit with status code '.$expectedStatusCode);

        $this->is($this->removeTrailingSpaces($output), $expectedOutput, 'test '.$name.' output');
    }

    private function removeTrailingSpaces(string $output): string
    {
        return preg_replace("/ *\n/", "\n", $output);
    }

    public function testFiles()
    {
        foreach ($this->provideTestCases() as $parameters) {
            $this->whenExecutePhpFileWillHaveStatusCodeAndOutput(...$parameters);
        }
    }

    private function provideTestCases()
    {
        yield [
            /* name */ 'pass',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
ok 1
1..1
# Looks like everything went fine.

EOF
        ];

        yield [
            /* name */ 'failed',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed.php at line 7)
#            got: false
#       expected: true
1..1
# Looks like you failed 1 tests of 1.

EOF
        ];

        yield [
            /* name */ 'failed_with_plan_less_than_total',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
1..1
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed_with_plan_less_than_total.php at line 7)
#            got: false
#       expected: true
ok 2
# Looks like you planned 1 tests but ran 1 extra.
# Looks like you failed 1 tests of 2.

EOF
        ];

        yield [
            /* name */ 'failed_with_plan_more_than_total',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
1..2
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed_with_plan_more_than_total.php at line 7)
#            got: false
#       expected: true
# Looks like you planned 2 tests but only ran 1.
# Looks like you failed 1 tests of 1.

EOF
        ];

        yield [
            /* name */ 'pass_with_plan_less_than_total',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
1..1
ok 1
ok 2
# Looks like you planned 1 tests but ran 1 extra.

EOF
        ];

        yield [
            /* name */ 'pass_with_plan_more_than_total',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
1..2
ok 1
# Looks like you planned 2 tests but only ran 1.

EOF
        ];

        yield [
            /* name */ 'pass_with_one_error',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'


  Notice: some user error message
  (in test/unit/vendor/lime/fixtures/pass_with_one_error.php on line
  11)


Exception trace:
  at test/unit/vendor/lime/fixtures/pass_with_one_error.php:11
  trigger_error() at test/unit/vendor/lime/fixtures/pass_with_one_error.php:11

ok 1
1..1
# Looks like everything went fine.

EOF
        ];

        yield [
            /* name */ 'pass_with_one_throw_exception',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'


  LogicException: some exception message
  (in
  test/unit/vendor/lime/fixtures/pass_with_one_throw_exception.php
  on line 7)


# Looks like everything went fine.

EOF
        ];

        yield [
            /* name */ 'skip',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
ok 1 # SKIP some skip message
1..1
# Looks like everything went fine.

EOF
        ];

        yield [
            /* name */ 'skip_with_plan',
            /* expectedStatusCode */ 0,
            /* expectedOutput */ <<<'EOF'
1..2
ok 1 # SKIP some skip message
ok 2 # SKIP some skip message
# Looks like everything went fine.

EOF
        ];
    }
}

(new lime_testTest())->run();
