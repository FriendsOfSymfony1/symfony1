<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for tasks that depends on a sfCommandApplication object.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
abstract class sfCommandApplicationTask extends sfTask
{
  /**
   * @var sfSymfonyCommandApplication
   */
  protected $commandApplication;

  /**
   * Sets the command application instance for this task.
   *
   * @param sfCommandApplication $commandApplication A sfCommandApplication instance
   */
  public function setCommandApplication(sfCommandApplication $commandApplication = null)
  {
    $this->commandApplication = $commandApplication;
  }

  /**
   * @see sfTask
   */
  public function log($messages)
  {
    if (null === $this->commandApplication || $this->commandApplication->isVerbose())
    {
      parent::log($messages);
    }
  }

  /**
   * @see sfTask
   */
  public function logSection($section, $message, $size = null, $style = 'INFO')
  {
    if (null === $this->commandApplication || $this->commandApplication->isVerbose())
    {
      parent::logSection($section, $message, $size, $style);
    }
  }

  /**
   * Creates a new task object.
   *
   * @param  string $name The name of the task
   *
   * @return sfTask
   *
   * @throws LogicException If the current task has no command application
   */
  protected function createTask($name)
  {
    if (null === $this->commandApplication)
    {
      throw new LogicException('Unable to create a task as no command application is associated with this task yet.');
    }

    $task = $this->commandApplication->getTaskToExecute($name);

    if ($task instanceof sfCommandApplicationTask)
    {
      $task->setCommandApplication($this->commandApplication);
    }

    return $task;
  }

  /**
   * Executes another task in the context of the current one.
   *
   * @param  string  $name      The name of the task to execute
   * @param  array   $arguments An array of arguments to pass to the task
   * @param  array   $options   An array of options to pass to the task
   *
   * @return Boolean The returned value of the task run() method
   *
   * @see createTask()
   */
  protected function runTask($name, $arguments = array(), $options = array())
  {
    return $this->createTask($name)->run($arguments, $options);
  }

}
