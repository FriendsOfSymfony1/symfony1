<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Outputs test code coverage.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTestCoverageTask extends sfBaseTask
{
    /**
     * @see sfTask
     */
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('test_name', sfCommandArgument::REQUIRED, 'A test file name or a test directory'),
            new sfCommandArgument('lib_name', sfCommandArgument::REQUIRED, 'A lib file name or a lib directory for wich you want to know the coverage'),
        ]);

        $this->addOptions([
            new sfCommandOption('detailed', null, sfCommandOption::PARAMETER_NONE, 'Output detailed information'),
        ]);

        $this->namespace = 'test';
        $this->name = 'coverage';
        $this->briefDescription = 'Outputs test code coverage';

        $this->detailedDescription = <<<'EOF'
The [test:coverage|INFO] task outputs the code coverage
given a test file or test directory
and a lib file or lib directory for which you want code
coverage:

  [./symfony test:coverage test/unit/model lib/model|INFO]

To output the lines not covered, pass the [--detailed|INFO] option:

  [./symfony test:coverage --detailed test/unit/model lib/model|INFO]
EOF;
    }

    /**
     * @see sfTask
     *
     * @param mixed $arguments
     * @param mixed $options
     */
    protected function execute($arguments = [], $options = [])
    {
        require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php';

        $coverage = $this->getCoverage($this->getTestHarness(['force_colors' => isset($options['color']) && $options['color']]), $options['detailed']);

        $testFiles = $this->getFiles(sfConfig::get('sf_root_dir').'/'.$arguments['test_name']);
        $max = count($testFiles);
        foreach ($testFiles as $i => $file) {
            $this->logSection('coverage', sprintf('running %s (%d/%d)', $file, $i + 1, $max));
            $coverage->process($file);
        }

        $coveredFiles = $this->getFiles(sfConfig::get('sf_root_dir').'/'.$arguments['lib_name']);
        $coverage->output($coveredFiles);
    }

    protected function getTestHarness($harnessOptions = [])
    {
        require_once __DIR__.'/sfLimeHarness.class.php';

        $harness = new sfLimeHarness($harnessOptions);
        $harness->addPlugins(array_map([$this->configuration, 'getPluginConfiguration'], $this->configuration->getPlugins()));
        $harness->base_dir = sfConfig::get('sf_root_dir');

        return $harness;
    }

    protected function getCoverage(lime_harness $harness, $detailed = false)
    {
        $coverage = new lime_coverage($harness);
        $coverage->verbose = $detailed;
        $coverage->base_dir = sfConfig::get('sf_root_dir');

        return $coverage;
    }

    protected function getFiles($directory)
    {
        if (is_dir($directory)) {
            return sfFinder::type('file')->name('*.php')->in($directory);
        }
        if (file_exists($directory)) {
            return [$directory];
        }

        throw new sfCommandException(sprintf('File or directory "%s" does not exist.', $directory));
    }
}
