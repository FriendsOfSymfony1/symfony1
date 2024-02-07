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
 * Inserts SQL for current model.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineGenerateMigrationTask extends \sfDoctrineBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->addArguments([
            new \sfCommandArgument('name', \sfCommandArgument::REQUIRED, 'The name of the migration'),
        ]);

        $this->addOptions([
            new \sfCommandOption('application', null, \sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new \sfCommandOption('env', null, \sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new \sfCommandOption('editor-cmd', null, \sfCommandOption::PARAMETER_REQUIRED, 'Open script with this command upon creation'),
        ]);

        $this->namespace = 'doctrine';
        $this->name = 'generate-migration';
        $this->briefDescription = 'Generate migration class';

        $this->detailedDescription = <<<'EOF'
The [doctrine:generate-migration|INFO] task generates migration template

  [./symfony doctrine:generate-migration AddUserEmailColumn|INFO]

You can provide an [--editor-cmd|COMMENT] option to open the new migration class in your
editor of choice upon creation:

  [./symfony doctrine:generate-migration AddUserEmailColumn --editor-cmd=mate|INFO]
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        $databaseManager = new \sfDatabaseManager($this->configuration);
        $config = $this->getCliConfig();

        $this->logSection('doctrine', sprintf('generating migration class named "%s"', $arguments['name']));

        if (!is_dir($config['migrations_path'])) {
            $this->getFilesystem()->mkdirs($config['migrations_path']);
        }

        $this->callDoctrineCli('generate-migration', ['name' => $arguments['name']]);

        $finder = \sfFinder::type('file')->sort_by_name()->name('*.php');
        if ($files = $finder->in($config['migrations_path'])) {
            $file = array_pop($files);

            $contents = file_get_contents($file);
            $contents = strtr(\sfToolkit::stripComments($contents), [
                "{\n\n" => "{\n",
                "\n}" => "\n}\n",
                '    ' => '  ',
            ]);
            file_put_contents($file, $contents);

            if (isset($options['editor-cmd'])) {
                $this->getFilesystem()->execute($options['editor-cmd'].' '.escapeshellarg($file));
            }
        }
    }
}
