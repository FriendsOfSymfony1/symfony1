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
 * Drops database, recreates it, inserts the sql and loads the data fixtures
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 *
 * @deprecated Use doctrine:build instead
 */
class sfDoctrineReloadDataTask extends sfDoctrineBaseTask
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
      new sfCommandOption('dir', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'The directories to look for fixtures'),
      new sfCommandOption('migrate', null, sfCommandOption::PARAMETER_NONE, 'Migrate instead of reset the database'),
      new sfCommandOption('append', null, sfCommandOption::PARAMETER_NONE, 'Don\'t delete current data in the database'),
    ));

    $this->aliases = array('doctrine-reload-data');    
    $this->namespace = 'doctrine';
    $this->name = 'reload-data';

    $this->briefDescription = 'Reloads databases and fixtures for your project';

    $this->detailedDescription = <<<EOF
The [doctrine:reload-data|INFO] task drops the database, recreates it and loads
fixtures:

  [php symfony doctrine:reload-data|INFO]
  
The task is equivalent to:

  [./symfony doctrine:drop-db|INFO]
  [./symfony doctrine:build-db|INFO]
  [./symfony doctrine:insert-sql|INFO]
  [./symfony doctrine:data-load|INFO]  
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $task = new sfDoctrineBuildTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);
    $task->setConfiguration($this->configuration);
    $ret = $task->run(array(), array(
      'no-confirmation' => $options['no-confirmation'],
      'db'              => true,
      'and-migrate'     => $options['migrate'],
      'and-load'        => $options['append'] ? false : (count($options['dir']) ? $options['dir'] : true),
      'and-append'      => $options['append'] ? (count($options['dir']) ? $options['dir'] : true) : false,
    ));

    return $ret;
  }
}
