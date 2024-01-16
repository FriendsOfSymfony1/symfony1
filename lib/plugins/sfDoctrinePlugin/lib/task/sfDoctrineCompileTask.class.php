<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/sfDoctrineBaseTask.class.php';

/**
 * Compile Doctrine.
 *
 * @author     Jerome Tamarelle <jerome@tamarelle.net>
 *
 * @version    SVN: $Id: sfDoctrineCompileTask.class.php 105 2012-03-22 16:26:34Z jtamarelle $
 */
class sfDoctrineCompileTask extends sfDoctrineBaseTask
{
    /**
     * @see sfTask
     */
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('database', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY, 'A specific database'),
        ]);

        $this->addOptions([
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Whether to force dropping of the database'),
        ]);

        $this->namespace = 'doctrine';
        $this->name = 'compile';
        $this->briefDescription = 'Compile doctrine into the cache directory';

        $this->detailedDescription = <<<'EOF'
The [doctrine:compile|INFO] task generated a compiled Doctrine file:

  [./symfony doctrine:compile|INFO]
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
        $databaseManager = new sfDatabaseManager($this->configuration);

        $compiledFile = sfConfig::get('sf_cache_dir').'/doctrine.compiled.php';

        if (file_exists($compiledFile)) {
            $this->logSection('error', $compiledFile.' already exists', null, 'ERROR');
            $this->logBlock('Run symfony:cache-clear first', 'INFO');

            return;
        }

        $drivers = [];
        foreach ($databaseManager->getNames() as $name) {
            $drivers[] = strtolower($databaseManager->getDatabase($name)->getDoctrineConnection()->getDriverName());
        }
        $drivers = array_unique($drivers);

        $this->logSection('compile', 'Included drivers: '.implode(', ', $drivers));

        Doctrine_Core::compile($compiledFile, $drivers);

        $this->logSection('file+', $compiledFile);
    }
}
