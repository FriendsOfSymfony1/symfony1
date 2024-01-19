<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/sfDoctrineBaseTask.class.php';

/**
 * Generate migrations from database.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineGenerateMigrationsDbTask extends \sfDoctrineBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->addOptions([
            new \sfCommandOption('application', null, \sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new \sfCommandOption('env', null, \sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        ]);

        $this->namespace = 'doctrine';
        $this->name = 'generate-migrations-db';
        $this->briefDescription = 'Generate migration classes from existing database connections';

        $this->detailedDescription = <<<'EOF'
The [doctrine:generate-migrations-db|INFO] task generates migration classes from
existing database connections:

  [./symfony doctrine:generate-migrations-db|INFO]
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        $databaseManager = new \sfDatabaseManager($this->configuration);
        $config = $this->getCliConfig();

        $this->logSection('doctrine', 'generating migration classes from database');

        if (!is_dir($config['migrations_path'])) {
            $this->getFilesystem()->mkdirs($config['migrations_path']);
        }

        $this->callDoctrineCli('generate-migrations-db', [
            'yaml_schema_path' => $this->prepareSchemaFile($config['yaml_schema_path']),
        ]);
    }
}
