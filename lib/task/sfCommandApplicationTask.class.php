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
  protected
    $commandApplication;

  private
    $mailer,
    $routing,
    $serviceContainer,
    $factoryConfiguration;

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

  /**
   * Returns a mailer instance.
   *
   * Notice that your task should accept an application option.
   * The mailer configuration is read from the current configuration
   * instance, which is automatically created according to the current
   * --application option.
   *
   * @return sfMailer A sfMailer instance
   */
  protected function getMailer()
  {
    if (null === $this->mailer)
    {
      $this->mailer = $this->initializeMailer();
    }

    return $this->mailer;
  }

  /**
   * Initialize mailer
   *
   * @return sfMailer A sfMailer instance
   */
  protected function initializeMailer()
  {
    $swift_dir = sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/lib';
    require_once $swift_dir.'/classes/Swift.php';
    Swift::registerAutoload($swift_dir.'/swift_init.php');

    $config = $this->getFactoryConfiguration();

    return new $config['mailer']['class']($this->dispatcher, $config['mailer']['param']);
  }

  /**
   * Returns a routing instance.
   *
   * Notice that your task should accept an application option.
   * The routing configuration is read from the current configuration
   * instance, which is automatically created according to the current
   * --application option.
   *
   * @return sfRouting A sfRouting instance
   */
  protected function getRouting()
  {
    if (null === $this->routing)
    {
      $this->routing = $this->initializeRouting();
    }

    return $this->routing;
  }

  /**
   * Initialize routing
   *
   * @return sfRouting A sfRouting instance
   */
  protected function initializeRouting()
  {
    $config = $this->getFactoryConfiguration();
    $params = array_merge($config['routing']['param'], array('load_configuration' => false, 'logging' => false));

    $handler = new sfRoutingConfigHandler();
    $routes = $handler->evaluate($this->configuration->getConfigPaths('config/routing.yml'));

    $routing = new $config['routing']['class']($this->dispatcher, null, $params);
    $routing->setRoutes($routes);

    $this->dispatcher->notify(new sfEvent($routing, 'routing.load_configuration'));

    return $routing;
  }

  /**
   * Returns the service container instance.
   *
   * Notice that your task should accept an application option.
   * The routing configuration is read from the current configuration
   * instance, which is automatically created according to the current
   * --application option.
   *
   * @return sfServiceContainer An application service container
   */
  protected function getServiceContainer()
  {
    if (null === $this->serviceContainer)
    {
      $class = require $this->configuration->getConfigCache()->checkConfig('config/services.yml', true);

      $this->serviceContainer = new $class();
      $this->serviceContainer->setService('sf_event_dispatcher', $this->dispatcher);
      $this->serviceContainer->setService('sf_formatter', $this->formatter);
    }

    return $this->serviceContainer;
  }

  /**
   * Retrieves a service from the service container.
   *
   * @param  string $id The service identifier
   *
   * @return object The service instance
   */
  public function getService($id)
  {
    return $this->getServiceContainer()->getService($id);
  }

  /**
   * Gets the factory configuration
   *
   * @return array
   */
  protected function getFactoryConfiguration()
  {
    if (null === $this->factoryConfiguration)
    {
      $this->factoryConfiguration = sfFactoryConfigHandler::getConfiguration($this->configuration->getConfigPaths('config/factories.yml'));
    }

    return $this->factoryConfiguration;
  }
}
