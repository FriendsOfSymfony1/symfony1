<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Unit test library.
 *
 * @author     Fabien Potencier <fabien.potencier@gmail.com>
 */
class lime_harness extends lime_registration
{
    public $options = [];
    public $php_cli;
    public $stats = [];
    public $output;
    public $full_output = false;

    public function __construct($options = [])
    {
        // for BC
        if (!is_array($options)) {
            $options = ['output' => $options];
        }

        $this->options = array_merge([
            'php_cli' => null,
            'force_colors' => false,
            'output' => null,
            'verbose' => false,
            'test_path' => sys_get_temp_dir(),
        ], $options);

        $this->php_cli = $this->find_php_cli($this->options['php_cli']);
        $this->output = $this->options['output'] ? $this->options['output'] : new lime_output($this->options['force_colors']);
    }

    protected function find_php_cli($php_cli = null)
    {
        if (is_null($php_cli)) {
            if (getenv('PHP_PATH')) {
                $php_cli = getenv('PHP_PATH');

                if (!is_executable($php_cli)) {
                    throw new Exception('The defined PHP_PATH environment variable is not a valid PHP executable.');
                }
            } else {
                $php_cli = PHP_BINDIR.DIRECTORY_SEPARATOR.'php';
            }
        }

        if (is_executable($php_cli)) {
            return $php_cli;
        }

        $path = getenv('PATH') ? getenv('PATH') : getenv('Path');
        $exe_suffixes = DIRECTORY_SEPARATOR == '\\' ? (getenv('PATHEXT') ? explode(PATH_SEPARATOR, getenv('PATHEXT')) : ['.exe', '.bat', '.cmd', '.com']) : [''];
        foreach (['php5', 'php'] as $php_cli) {
            foreach ($exe_suffixes as $suffix) {
                foreach (explode(PATH_SEPARATOR, $path) as $dir) {
                    $file = $dir.DIRECTORY_SEPARATOR.$php_cli.$suffix;
                    if (is_executable($file)) {
                        return $file;
                    }
                }
            }
        }

        throw new Exception('Unable to find PHP executable.');
    }

    public function to_array()
    {
        $results = [];
        foreach ($this->stats['files'] as $stat) {
            $results = array_merge($results, $stat['output']);
        }

        return $results;
    }

    public function to_xml()
    {
        return lime_test::to_xml($this->to_array());
    }

    public function run()
    {
        if (!count($this->files)) {
            throw new Exception('You must register some test files before running them!');
        }

        // sort the files to be able to predict the order
        sort($this->files);

        $this->stats = [
            'files' => [],
            'failed_files' => [],
            'failed_tests' => 0,
            'total' => 0,
        ];

        foreach ($this->files as $file) {
            $this->stats['files'][$file] = [];
            $stats = &$this->stats['files'][$file];

            $test_file = tempnam($this->options['test_path'], 'lime_test').'.php';
            $result_file = tempnam($this->options['test_path'], 'lime_result');
            file_put_contents($test_file, <<<EOF
<?php
function lime_shutdown()
{
  file_put_contents('{$result_file}', serialize(lime_test::to_array()));
}
register_shutdown_function('lime_shutdown');
include('{$file}');
EOF
            );

            ob_start();
            $return = $this->executePhpFile($test_file);
            ob_end_clean();
            unlink($test_file);

            $stats['status_code'] = $return;
            $output = file_get_contents($result_file);
            $stats['output'] = $output ? unserialize($output) : '';
            if (!$stats['output']) {
                $stats['output'] = $this->makeOutputForMissingTestReport($file);
            }
            unlink($result_file);

            $file_stats = &$stats['output'][0]['stats'];

            $delta = $this->computePlanDeltaFromFileStats($file_stats);

            $stats['status'] = $this->computeStatusWithCodeAndFileStats(
                $stats['status_code'],
                $file_stats
            );

            if ('ok' !== $stats['status']) {
                $this->stats['failed_files'][] = $file;
            }

            $this->stats['total'] += $file_stats['total'];

            if ($delta > 0) {
                $this->stats['failed_tests'] += $delta;
                $this->stats['total'] += $delta;
            }

            if ($file_stats['failed']) {
                $this->stats['failed_tests'] += count($file_stats['failed']);
            }

            $this->writeFileSummary($file, $stats['status']);

            $this->writeFileDetails($stats, $file_stats, $delta);
        }

        if (count($this->stats['failed_files'])) {
            $format = '%-30s  %4s  %5s  %5s  %5s  %s';
            $this->output->echoln(sprintf($format, 'Failed Test', 'Stat', 'Total', 'Fail', 'Errors', 'List of Failed'));
            $this->output->echoln('--------------------------------------------------------------------------');
            foreach ($this->stats['files'] as $file => $stat) {
                if (!in_array($file, $this->stats['failed_files'])) {
                    continue;
                }
                $relative_file = $this->get_relative_file($file);

                if (isset($stat['output'][0])) {
                    $this->output->echoln(sprintf($format,
                        substr($relative_file, -min(30, strlen($relative_file))),
                        $stat['status_code'],
                        count($stat['output'][0]['stats']['failed'])
                          + count($stat['output'][0]['stats']['passed']),
                        count($stat['output'][0]['stats']['failed']),
                        count($stat['output'][0]['stats']['errors']),
                        implode(' ', $stat['output'][0]['stats']['failed'])
                    ));
                } else {
                    $this->output->echoln(sprintf($format, substr($relative_file, -min(30, strlen($relative_file))), $stat['status_code'], '', '', ''));
                }
            }

            $this->output->red_bar(sprintf('Failed %d/%d test scripts, %.2f%% okay. %d/%d subtests failed, %.2f%% okay.',
                $nb_failed_files = count($this->stats['failed_files']),
                $nb_files = count($this->files),
                ($nb_files - $nb_failed_files) * 100 / $nb_files,
                $nb_failed_tests = $this->stats['failed_tests'],
                $nb_tests = $this->stats['total'],
                $nb_tests > 0 ? ($nb_tests - $nb_failed_tests) * 100 / $nb_tests : 0
            ));

            if ($this->options['verbose']) {
                foreach ($this->to_array() as $testsuite) {
                    $first = true;
                    foreach ($testsuite['stats']['failed'] as $testcase) {
                        if (!isset($testsuite['tests'][$testcase]['file'])) {
                            continue;
                        }

                        if ($first) {
                            $this->output->echoln('');
                            $this->output->error($this->get_relative_file($testsuite['file']).$this->extension);
                            $first = false;
                        }

                        $this->output->comment(sprintf('  at %s line %s', $this->get_relative_file($testsuite['tests'][$testcase]['file']).$this->extension, $testsuite['tests'][$testcase]['line']));
                        $this->output->info('  '.$testsuite['tests'][$testcase]['message']);
                        if (isset($testsuite['tests'][$testcase]['error'])) {
                            $this->output->echoln($testsuite['tests'][$testcase]['error'], null, false);
                        }
                    }
                }
            }
        } else {
            $this->output->green_bar(' All tests successful.');
            $this->output->green_bar(sprintf(' Files=%d, Tests=%d', count($this->files), $this->stats['total']));
        }

        return $this->stats['failed_files'] ? false : true;
    }

    private function makeOutputForMissingTestReport(string $file): array
    {
        return [
            [
                'file' => $file,
                'tests' => [],
                'stats' => [
                    'plan' => null,
                    'total' => 0,
                    'failed' => [],
                    'passed' => [],
                    'skipped' => [],
                    'errors' => [
                        [
                            'message' => 'Missing test report. It is probably due to a Parse error.',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function computePlanDeltaFromFileStats(array $fileStats): int
    {
        if ($fileStats['plan']) {
            return $fileStats['plan'] - $fileStats['total'];
        }

        return 0;
    }

    private function computeStatusWithCodeAndFileStats(int $statusCode, array $fileStats): string
    {
        if (0 === $statusCode) {
            return 'ok';
        }

        if ($fileStats['failed']) {
            return 'not ok';
        }

        if ($fileStats['errors']) {
            return 'errors';
        }

        return 'dubious';
    }

    private function writeFileSummary(string $file, string $status): void
    {
        $relativeFile = $this->get_relative_file($file);

        if (true === $this->full_output) {
            $this->output->echoln(sprintf('%s%s%s', $relativeFile, '.....', $status));
        } else {
            $this->output->echoln(sprintf('%s%s%s',
                substr($relativeFile, -min(67, strlen($relativeFile))),
                str_repeat('.', 70 - min(67, strlen($relativeFile))),
                $status
            ));
        }
    }

    private function writeFileDetails(array $stats, array $fileStats, int $delta): void
    {
        if ('dubious' === $stats['status']) {
            $this->output->echoln(sprintf('    Test returned status %s', $stats['status_code']));
        }

        if ($delta > 0) {
            $this->output->echoln(sprintf('    Looks like you planned %d tests but only ran %d.', $fileStats['plan'], $fileStats['total']));
        } elseif ($delta < 0) {
            $this->output->echoln(sprintf('    Looks like you planned %s test but ran %s extra.', $fileStats['plan'], $fileStats['total'] - $fileStats['plan']));
        }

        if (false !== $fileStats && $fileStats['failed']) {
            $this->output->echoln(sprintf('    Failed tests: %s', implode(', ', $fileStats['failed'])));
        }

        if (false !== $fileStats && $fileStats['errors']) {
            $this->output->echoln('    Errors:');

            $error_count = count($fileStats['errors']);
            for ($i = 0; $i < 3 && $i < $error_count; ++$i) {
                $this->output->echoln('    - '.$fileStats['errors'][$i]['message'], null, false);
            }
            if ($error_count > 3) {
                $this->output->echoln(sprintf('    ... and %s more', $error_count - 3));
            }
        }
    }

    public function get_failed_files()
    {
        return isset($this->stats['failed_files']) ? $this->stats['failed_files'] : [];
    }

    /**
     * The command fails if the path to php interpreter contains spaces.
     * The only workaround is adding a "nop" command call before the quoted command.
     * The weird "cd &".
     *
     * see http://trac.symfony-project.org/ticket/5437
     */
    public function executePhpFile(string $phpFile): int
    {
        passthru(sprintf('cd & %s %s 2>&1', escapeshellarg($this->php_cli), escapeshellarg($phpFile)), $return);

        return $return;
    }
}
