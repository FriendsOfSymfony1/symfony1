<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Drops Databases, Creates Databases, Generates Doctrine model, SQL, initializes database, load data and run 
 * all test suites
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 *
 * @deprecated Use doctrine:build and test:all instead
 */
class sfDoctrineBuildAllReloadTestAllTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not ask for confirmation'),
      new sfCommandOption('skip-forms', 'F', sfCommandOption::PARAMETER_NONE, 'Skip generating forms'),
      new sfCommandOption('migrate', null, sfCommandOption::PARAMETER_NONE, 'Migrate instead of reset the database'),
      new sfCommandOption('dir', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'The directories to look for fixtures'),
      new sfCommandOption('append', null, sfCommandOption::PARAMETER_NONE, 'Don\'t delete current data in the database'),
    ));

    $this->aliases = array('doctrine-build-all-reload-test-all');
    $this->namespace = 'doctrine';
    $this->name = 'build-all-reload-test-all';
    $this->briefDescription = 'Generates Doctrine model, SQL, initializes database, load data and run all tests';

    $this->detailedDescription = <<<EOF
The [doctrine:build-all-reload|INFO] task is a shortcut for four other tasks:

  [./symfony doctrine:build-all-reload-test-all frontend|INFO]

The task is equivalent to:
  
  [./symfony doctrine:drop-db|INFO]
  [./symfony doctrine:build-db|INFO]
  [./symfony doctrine:build-model|INFO]
  [./symfony doctrine:insert-sql|INFO]
  [./symfony doctrine:data-load|INFO]
  [./symfony test-all|INFO]

The task takes an application argument because of the [doctrine:data-load|COMMENT]
task. See [doctrine:data-load|COMMENT] help page for more information.

Include the [--migrate|COMMENT] option if you would like to run your project's
migrations rather than inserting the Doctrine SQL.

  [./symfony doctrine:build-all-reload-test-all --migrate|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $buildAllReload = new sfDoctrineBuildAllReloadTask($this->dispatcher, $this->formatter);
    $buildAllReload->setCommandApplication($this->commandApplication);
    $buildAllReload->setConfiguration($this->configuration);
    $ret = $buildAllReload->run(array(), array(
      'dir'             => $options['dir'],
      'append'          => $options['append'],
      'skip-forms'      => $options['skip-forms'],
      'no-confirmation' => $options['no-confirmation'],
      'migrate'         => $options['migrate'],
    ));

    if ($ret)
    {
      return $ret;
    }

    $this->logSection('doctrine', 'running test suite');
    
    $testAll = new sfTestAllTask($this->dispatcher, $this->formatter);
    $testAll->setCommandApplication($this->commandApplication);
    $testAll->setConfiguration($this->configuration);
    $testAll->run();
  }
}