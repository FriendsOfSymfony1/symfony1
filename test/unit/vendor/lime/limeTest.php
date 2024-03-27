<?php

require_once __DIR__.'/../../../bootstrap/unit.php';

function removeTrailingSpaces(string $output): string
{
    return preg_replace("/ *\n/", "\n", $output);
}

function whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput)
{
    $test->diag($name);

    ob_start();
    $exitCode = (new lime_harness())->executePhpFile(__DIR__.'/fixtures/'.$name.'.php');
    $output = ob_get_clean();

    $test->is($exitCode, $expectedStatusCode, 'with test '.$name.' will exit with status code '.$expectedStatusCode);

    $test->is(removeTrailingSpaces($output), $expectedOutput, 'test '.$name.' output');
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

    $test->is(removeTrailingSpaces($output), $expectedOutput, 'test harness result');
}

class lime_no_colorizer extends lime_colorizer
{
    public function __construct()
    {
    }
}

$test = new lime_test(18);

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
    - Notice: some use error message
Failed Test                     Stat  Total   Fail  Errors  List of Failed
--------------------------------------------------------------------------
e/fixtures/pass_with_one_error     1      1      0      1
Failed 1/1 test scripts, 0.00% okay. 0/1 subtests failed, 100.00% okay.

EOF;
$message = 'with at least one error will fail the overall test suite';
whenExecuteHarnessWithFilesWillHaveResultAndOutput($test, $files, $expectedOverallSucceed, $expectedOutput, $message);


$name = 'pass';
$expectedStatusCode = 0;
$expectedOutput = <<<'EOF'
ok 1
1..1
# Looks like everything went fine.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);

$name = 'failed';
$expectedStatusCode = 1;
$expectedOutput = <<<'EOF'
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed.php at line 7)
#            got: false
#       expected: true
1..1
# Looks like you failed 1 tests of 1.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);

$name = 'failed_with_plan_less_than_total';
$expectedStatusCode = 1;
$expectedOutput = <<<'EOF'
1..1
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed_with_plan_less_than_total.php at line 7)
#            got: false
#       expected: true
ok 2
# Looks like you planned 1 tests but ran 1 extra.
# Looks like you failed 1 tests of 2.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);

$name = 'failed_with_plan_more_than_total';
$expectedStatusCode = 1;
$expectedOutput = <<<'EOF'
1..2
not ok 1
#     Failed test (./test/unit/vendor/lime/fixtures/failed_with_plan_more_than_total.php at line 7)
#            got: false
#       expected: true
# Looks like you planned 2 tests but only ran 1.
# Looks like you failed 1 tests of 1.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);

$name = 'pass_with_plan_less_than_total';
$expectedStatusCode = 255;
$expectedOutput = <<<'EOF'
1..1
ok 1
ok 2
# Looks like you planned 1 tests but ran 1 extra.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);

$name = 'pass_with_plan_more_than_total';
$expectedStatusCode = 255;
$expectedOutput = <<<'EOF'
1..2
ok 1
# Looks like you planned 2 tests but only ran 1.

EOF;
whenExecutePhpFileWillHaveStatusCodeAndOutput($harness, $test, $name, $expectedStatusCode, $expectedOutput);
