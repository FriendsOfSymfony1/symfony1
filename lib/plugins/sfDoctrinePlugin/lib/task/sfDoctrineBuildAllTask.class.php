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
 * Generates Doctrine model, SQL and initializes the database.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 *
 * @deprecated Use doctrine:build instead
 */
class sfDoctrineBuildAllTask extends sfDoctrineBaseTask
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
    ));
  
    $this->aliases = array('doctrine-build-all');
    $this->namespace = 'doctrine';
    $this->name = 'build-all';
    $this->briefDescription = 'Generates Doctrine model, SQL and initializes the database';

    $this->detailedDescription = <<<EOF
The [doctrine:build-all|INFO] task is a shortcut for three other tasks:

  [./symfony doctrine:build-all|INFO]

The task is equivalent to:

  [./symfony doctrine:build-model|INFO]
  [./symfony doctrine:build-sql|INFO]
  [./symfony doctrine:build-forms|INFO]
  [./symfony doctrine:insert-sql|INFO]

See those three tasks help page for more information.

To bypass the confirmation, you can pass the [no-confirmation|COMMENT]
option:

  [./symfony doctrine:buil-all-load --no-confirmation|INFO]

Include the [--migrate|COMMENT] option if you would like to run your project's
migrations rather than inserting the Doctrine SQL.

  [./symfony doctrine:build-all --migrate|INFO]

This is equivalent to:

  [./symfony doctrine:build-model|INFO]
  [./symfony doctrine:build-sql|INFO]
  [./symfony doctrine:build-forms|INFO]
  [./symfony doctrine:migrate|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $task = new sfDoctrineBuildTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);
    $task->setConfiguration($this->configuration);
    $ret = $task->run(array(), array(
      'no-confirmation' => $options['no-confirmation'],
      'db'              => true,
      'model'           => true,
      'forms'           => !$options['skip-forms'],
      'filters'         => !$options['skip-forms'],
      'sql'             => true,
      'and-migrate'     => $options['migrate'],
    ));

    return $ret;
  }
}
