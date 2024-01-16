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
 * Creates a schema.yml from an existing database.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineBuildSchemaTask extends sfDoctrineBaseTask
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
        $this->name = 'build-schema';
        $this->briefDescription = 'Creates a schema from an existing database';

        $this->detailedDescription = <<<'EOF'
The [doctrine:build-schema|INFO] task introspects a database to create a schema:

  [./symfony doctrine:build-schema|INFO]

The task creates a yml file in [config/doctrine|COMMENT]
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
        $this->logSection('doctrine', 'generating yaml schema from database');

        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->callDoctrineCli('generate-yaml-db');
    }
}
