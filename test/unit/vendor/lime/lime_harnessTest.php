<?php

require_once __DIR__.'/../../../bootstrap/unit.php';
require_once __DIR__.'/tools/TestCase.php';
require_once __DIR__.'/tools/lime_no_colorizer.php';

/**
 * @internal
 *
 * @coversNothing
 */
class lime_harnessTest extends TestCase
{
    private function makeHarness(): lime_harness
    {
        $harness = new lime_harness();
        $harness->output->colorizer = new lime_no_colorizer();

        return $harness;
    }

    private function assertHarnessWithOverallSucceedAndOutput(lime_harness $harness, $expectedOverallSucceed, string $expectedOutput)
    {
        ob_start();
        $allTestsSucceed = $harness->run();
        $output = ob_get_clean();

        $this->is($expectedOverallSucceed, $allTestsSucceed, 'expect overall test '.($expectedOverallSucceed ? 'succeed' : 'failed'));

        $this->is($this->removeTrailingSpaces($output), $expectedOutput, 'test harness result output');
    }

    private function removeTrailingSpaces(string $output): string
    {
        return preg_replace("/ *\n/", "\n", $output);
    }

    public function testOnlyOneTestFile(): void
    {
        foreach ($this->provideOnlyOneTestFile() as [$name, $expectedOverallSucceed, $expectedOutput]) {
            $this->info($name);

            $harness = $this->makeHarness();

            $harness->register([
                __DIR__."/fixtures/{$name}.php",
            ]);

            $this->assertHarnessWithOverallSucceedAndOutput($harness, $expectedOverallSucceed, $expectedOutput);
        }
    }

    private function provideOnlyOneTestFile(): iterable
    {
        yield [
            /* name */ 'pass',
            /* expectedOverallSucceed */ true,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass..................................ok
 All tests successful.
 Files=1, Tests=1

EOF
        ];

        yield [
            /* name */ 'pass_with_plan_less_than_total',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_plan_less_than_total........dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
pass_with_plan_less_than_total   255      2      0      0
Failed 1/1 test scripts, 0.00% okay. 0/2 subtests failed, 100.00% okay.

EOF
        ];

        yield [
            /* name */ 'pass_with_plan_more_than_total',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_plan_more_than_total........dubious
    Test returned status 255
    Looks like you planned 2 tests but only ran 1.
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
pass_with_plan_more_than_total   255      1      0      0
Failed 1/1 test scripts, 0.00% okay. 1/2 subtests failed, 50.00% okay.

EOF
        ];

        yield [
            /* name */ 'pass_with_one_error',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_error...................errors
    Errors:
    - Notice: some user error message
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
e/fixtures/pass_with_one_error     0      1      0      1
Failed 1/1 test scripts, 0.00% okay. 0/1 subtests failed, 100.00% okay.

EOF
        ];

        yield [
            /* name */ 'pass_with_one_throw_exception',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_throw_exception.........errors
    Errors:
    - LogicException: some exception message
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
/pass_with_one_throw_exception     0      0      0      1
Failed 1/1 test scripts, 0.00% okay. 0/0 subtests failed, 0.00% okay.

EOF
        ];

        yield [
            /* name */ 'pass_with_one_parse_error',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_parse_error.............dubious
    Test returned status 255
    Failed tests: 0
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
ures/pass_with_one_parse_error   255      1      1      0  0
Failed 1/1 test scripts, 0.00% okay. 1/0 subtests failed, 0.00% okay.

EOF
        ];

        yield [
            /* name */ 'skip',
            /* expectedOverallSucceed */ true,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/skip..................................ok
 All tests successful.
 Files=1, Tests=1

EOF
        ];

        yield [
            /* name */ 'skip_with_plan',
            /* expectedOverallSucceed */ true,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/skip_with_plan........................ok
 All tests successful.
 Files=1, Tests=2

EOF
        ];

        yield [
            /* name */ 'failed',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/failed................................not ok
    Failed tests: 1
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
it/vendor/lime/fixtures/failed     0      1      1      0  1
Failed 1/1 test scripts, 0.00% okay. 1/1 subtests failed, 0.00% okay.

EOF
        ];

        yield [
            /* name */ 'failed_with_plan_less_than_total',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/failed_with_plan_less_than_total......dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
    Failed tests: 1
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
iled_with_plan_less_than_total   255      2      1      0  1
Failed 1/1 test scripts, 0.00% okay. 1/2 subtests failed, 50.00% okay.

EOF
        ];

        yield [
            /* name */ 'failed_with_plan_more_than_total',
            /* expectedOverallSucceed */ false,
            /* expectedOutput */ <<<'EOF'
test/unit/vendor/lime/fixtures/failed_with_plan_more_than_total......dubious
    Test returned status 255
    Looks like you planned 2 tests but only ran 1.
    Failed tests: 1
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
iled_with_plan_more_than_total   255      1      1      0  1
Failed 1/1 test scripts, 0.00% okay. 2/2 subtests failed, 0.00% okay.

EOF
        ];
    }

    public function test_registerFilesWithGlob_thenRunThemAll(): void
    {
        $harness = $this->makeHarness();

        $harness->register_glob(__DIR__.'/fixtures/*.php');

        $this->assertHarnessWithOverallSucceedAndOutput($harness, false, <<<'EOF'
test/unit/vendor/lime/fixtures/failed................................not ok
    Failed tests: 1
test/unit/vendor/lime/fixtures/failed_with_plan_less_than_total......dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
    Failed tests: 1
test/unit/vendor/lime/fixtures/failed_with_plan_more_than_total......dubious
    Test returned status 255
    Looks like you planned 2 tests but only ran 1.
    Failed tests: 1
test/unit/vendor/lime/fixtures/pass..................................ok
test/unit/vendor/lime/fixtures/pass_with_one_error...................errors
    Errors:
    - Notice: some user error message
test/unit/vendor/lime/fixtures/pass_with_one_parse_error.............dubious
    Test returned status 255
    Failed tests: 0
test/unit/vendor/lime/fixtures/pass_with_one_throw_exception.........errors
    Errors:
    - LogicException: some exception message
test/unit/vendor/lime/fixtures/pass_with_plan_less_than_total........dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
test/unit/vendor/lime/fixtures/pass_with_plan_more_than_total........dubious
    Test returned status 255
    Looks like you planned 2 tests but only ran 1.
test/unit/vendor/lime/fixtures/skip..................................ok
test/unit/vendor/lime/fixtures/skip_with_plan........................ok
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
it/vendor/lime/fixtures/failed     0      1      1      0  1
iled_with_plan_less_than_total   255      2      1      0  1
iled_with_plan_more_than_total   255      1      1      0  1
e/fixtures/pass_with_one_error     0      1      0      1
ures/pass_with_one_parse_error   255      1      1      0  0
/pass_with_one_throw_exception     0      0      0      1
pass_with_plan_less_than_total   255      2      0      0
pass_with_plan_more_than_total   255      1      0      0
Failed 8/11 test scripts, 27.27% okay. 6/14 subtests failed, 57.14% okay.

EOF);
    }
}

(new lime_harnessTest())->run();
