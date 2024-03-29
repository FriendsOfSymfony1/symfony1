<?php

require_once __DIR__.'/../../../bootstrap/unit.php';

function removeTrailingSpaces(string $output): string
{
    return preg_replace("/ *\n/", "\n", $output);
}

function whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message)
{
    $harness = new lime_harness();
    $harness->output->colorizer = new lime_no_colorizer();

    $harness->register($files);

    ob_start();
    $allTestsSucceed = $harness->run();
    $output = ob_get_clean();

    $test->is($expectedOverallSucceed, $allTestsSucceed, $message);

    $test->is(removeTrailingSpaces($output), $expectedOutput, 'test harness result output');
}

class lime_no_colorizer extends lime_colorizer
{
    public function __construct()
    {
    }
}

$test = new lime_test(12);

$files = [
    __DIR__.'/fixtures/failed.php',
    __DIR__.'/fixtures/failed_with_plan_less_than_total.php',
    __DIR__.'/fixtures/failed_with_plan_more_than_total.php',
    __DIR__.'/fixtures/pass.php',
    __DIR__.'/fixtures/pass_with_plan_less_than_total.php',
    __DIR__.'/fixtures/pass_with_plan_more_than_total.php',
];
$expectedOverallSucceed = false;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/failed................................not ok
    Failed tests: 1
test/unit/vendor/lime/fixtures/failed_with_plan_less_than_total......not ok
    Looks like you planned 1 test but ran 1 extra.
    Failed tests: 1
test/unit/vendor/lime/fixtures/failed_with_plan_more_than_total......not ok
    Looks like you planned 2 tests but only ran 1.
    Failed tests: 1
test/unit/vendor/lime/fixtures/pass..................................ok
test/unit/vendor/lime/fixtures/pass_with_plan_less_than_total........dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
test/unit/vendor/lime/fixtures/pass_with_plan_more_than_total........dubious
    Test returned status 255
    Looks like you planned 2 tests but only ran 1.
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
it/vendor/lime/fixtures/failed     1      1      1      0  1
iled_with_plan_less_than_total     1      2      1      0  1
iled_with_plan_more_than_total     1      1      1      0  1
pass_with_plan_less_than_total   255      2      0      0
pass_with_plan_more_than_total   255      1      0      0
Failed 5/6 test scripts, 16.67% okay. 5/10 subtests failed, 50.00% okay.

EOF;
$message = 'with at least one failed test file will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$files = [__DIR__.'/fixtures/pass_with_plan_less_than_total.php'];
$expectedOverallSucceed = false;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_plan_less_than_total........dubious
    Test returned status 255
    Looks like you planned 1 test but ran 1 extra.
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
pass_with_plan_less_than_total   255      2      0      0
Failed 1/1 test scripts, 0.00% okay. 0/2 subtests failed, 100.00% okay.

EOF;
$message = 'with at least one test file that not follow the plan will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$files = [__DIR__.'/fixtures/pass_with_one_error.php'];
$expectedOverallSucceed = false;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_error...................errors
    Errors:
    - Notice: some user error message
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
e/fixtures/pass_with_one_error     1      1      0      1
Failed 1/1 test scripts, 0.00% okay. 0/1 subtests failed, 100.00% okay.

EOF;
$message = 'with at least one error will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$files = [__DIR__.'/fixtures/pass_with_one_throw_exception.php'];
$expectedOverallSucceed = false;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_throw_exception.........errors
    Errors:
    - LogicException: some exception message
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
/pass_with_one_throw_exception     1      0      0      1
Failed 1/1 test scripts, 0.00% okay. 0/0 subtests failed, 0.00% okay.

EOF;
$message = 'with at least one thrown Exception will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$files = [__DIR__.'/fixtures/pass.php'];
$expectedOverallSucceed = true;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/pass..................................ok
 All tests successful.
 Files=1, Tests=1

EOF;
$message = 'with all tests passes without error and exception will succeed the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$files = [__DIR__.'/fixtures/pass_with_one_parse_error.php'];
$expectedOverallSucceed = false;
$expectedOutput = <<<'EOF'
test/unit/vendor/lime/fixtures/pass_with_one_parse_error.............errors
    Errors:
    - Missing test report. It is probably due to a Parse error.
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
ures/pass_with_one_parse_error   255      0      0      1
Failed 1/1 test scripts, 0.00% okay. 0/0 subtests failed, 0.00% okay.

EOF;
$message = 'with parse error will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);
