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
 * Inserts SQL for current model.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineGenerateMigrationsModelsTask extends sfDoctrineBaseTask
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
        $this->name = 'generate-migrations-models';
        $this->briefDescription = 'Generate migration classes from an existing set of models';

        $this->detailedDescription = <<<'EOF'
The [doctrine:generate-migrations-models|INFO] task generates migration classes
from an existing set of models:

  [./symfony doctrine:generate-migrations-models|INFO]
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
        $config = $this->getCliConfig();

        $this->logSection('doctrine', 'generating migration classes from models');

        if (!is_dir($config['migrations_path'])) {
            $this->getFilesystem()->mkdirs($config['migrations_path']);
        }

        $this->callDoctrineCli('generate-migrations-models');
    }
}
