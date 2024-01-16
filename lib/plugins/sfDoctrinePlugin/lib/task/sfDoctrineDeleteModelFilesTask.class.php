<?php

/*
 * This file is part of the symfony package.
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/sfDoctrineBaseTask.class.php';

/**
 * Delete all generated files associated with a Doctrine model. Forms, filters, etc.
 *
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineDeleteModelFilesTask extends sfDoctrineBaseTask
{
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('name', sfCommandArgument::REQUIRED | sfCommandArgument::IS_ARRAY, 'The name of the model you wish to delete all related files for.'),
        ]);

        $this->addOptions([
            new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not ask for confirmation'),
            new sfCommandOption('prefix', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Class prefix to remove'),
            new sfCommandOption('suffix', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Class suffix to remove'),
            new sfCommandOption('extension', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'Filename extension to remove'),
        ]);

        $this->namespace = 'doctrine';
        $this->name = 'delete-model-files';
        $this->briefDescription = 'Delete all the related auto generated files for a given model name.';

        $this->detailedDescription = <<<'EOF'
The [doctrine:delete-model-files|INFO] task deletes all files associated with certain
models:

  [./symfony doctrine:delete-model-files Article Author|INFO]
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
        $paths = array_merge(
            [
                sfConfig::get('sf_lib_dir').'/model/doctrine',
                sfConfig::get('sf_lib_dir').'/form/doctrine',
                sfConfig::get('sf_lib_dir').'/filter/doctrine',
            ],
            $this->configuration->getPluginSubPaths('/lib/model/doctrine'),
            $this->configuration->getPluginSubPaths('/lib/form/doctrine'),
            $this->configuration->getPluginSubPaths('/lib/filter/doctrine')
        );

        $prefixPattern = $this->valuesToRegex($options['prefix'] ? $options['prefix'] : ['', 'Base', 'Plugin']);
        $suffixPattern = $this->valuesToRegex($options['suffix'] ? $options['suffix'] : ['', 'Table', 'Form', 'FormFilter']);
        $extensionPattern = $this->valuesToRegex($options['extension'] ? $options['extension'] : ['.php', '.class.php']);

        $total = 0;

        foreach ($arguments['name'] as $modelName) {
            $finder = sfFinder::type('file')->name('/^'.$prefixPattern.$modelName.$suffixPattern.$extensionPattern.'$/');
            $files = $finder->in($paths);

            if ($files) {
                if (!$options['no-confirmation'] && !$this->askConfirmation(array_merge(
                    ['The following '.$modelName.' files will be deleted:', ''],
                    array_map(function ($v) { return ' - '.sfDebug::shortenFilePath($v); }, $files),
                    ['', 'Continue? (y/N)']
                ), 'QUESTION_LARGE', false)) {
                    $this->logSection('doctrine', 'Aborting delete of "'.$modelName.'" files');

                    continue;
                }

                $this->logSection('doctrine', 'Deleting "'.$modelName.'" files');
                $this->getFilesystem()->remove($files);

                $total += count($files);
            } else {
                $this->logSection('doctrine', 'No files found for the model named "'.$modelName.'"');
            }
        }

        $this->logSection('doctrine', 'Deleted a total of '.$total.' file(s)');
    }

    /**
     * Converts an array of values to a regular expression pattern fragment.
     *
     * @param array  $values    An array of values for the pattern
     * @param string $delimiter The regular expression delimiter
     *
     * @return string A regular expression fragment
     */
    protected function valuesToRegex($values, $delimiter = '/')
    {
        if (false !== $pos = array_search('', $values)) {
            $required = false;
            unset($values[$pos]);
        } else {
            $required = true;
        }

        if (count($values)) {
            $regex = '(';
            foreach ($values as $i => $value) {
                $regex .= preg_quote($value, $delimiter);
                if (isset($values[$i + 1])) {
                    $regex .= '|';
                }
            }
            $regex .= ')';

            if (!$required) {
                $regex .= '?';
            }

            return $regex;
        }
    }
}
