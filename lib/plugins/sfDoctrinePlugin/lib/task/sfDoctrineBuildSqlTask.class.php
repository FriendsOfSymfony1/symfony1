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
 * Create SQL for the current model.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineBuildSqlTask extends sfDoctrineBaseTask
{
    /**
     * @see sfTask
     */
    protected function configure()
    {
        $this->addOptions([
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        ]);

        $this->namespace = 'doctrine';
        $this->name = 'build-sql';
        $this->briefDescription = 'Creates SQL for the current model';

        $this->detailedDescription = <<<'EOF'
The [doctrine:build-sql|INFO] task creates SQL statements for table creation:

  [./symfony doctrine:build-sql|INFO]

The generated SQL is optimized for the database configured in [config/databases.yml|COMMENT]:

  [doctrine.database = mysql|INFO]
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
        $this->logSection('doctrine', 'generating sql for models');

        $path = sfConfig::get('sf_data_dir').'/sql';
        if (!is_dir($path)) {
            $this->getFilesystem()->mkdirs($path);
        }

        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->callDoctrineCli('generate-sql');
    }
}
