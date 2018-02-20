<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceContainer is a dependency injection container.
 *
 * It gives access to object instances (services), and parameters.
 *
 * Services and parameters are simple key/pair stores.
 *
 * Parameters keys are case insensitive.
 *
 * A service id can contain lowercased letters, digits, underscores, and dots.
 * Underscores are used to separate words, and dots to group services
 * under namespaces:
 *
 * <ul>
 *   <li>request</li>
 *   <li>mysql_session_storage</li>
 *   <li>symfony.mysql_session_storage</li>
 * </ul>
 *
 * A service can also be defined by creating a method named
 * getXXXService(), where XXX is the camelized version of the id:
 *
 * <ul>
 *   <li>request -> getRequestService()</li>
 *   <li>mysql_session_storage -> getMysqlSessionStorageService()</li>
 *   <li>symfony.mysql_session_storage -> getSymfony_MysqlSessionStorageService()</li>
 * </ul>
 *
 * @package    symfony
 * @subpackage service
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfServiceContainer implements sfServiceContainerInterface
{
  protected
    $serviceIds = array(),
    $parameters = array(),
    $services   = array(),
    $count      = 0;

  /**
   * Constructor.
   *
   * @param array $parameters An array of parameters
   */
  public function __construct(array $parameters = array())
  {
    $this->setParameters($parameters);
    $this->setService('service_container', $this);
  }

  /**
   * Sets the service container parameters.
   *
   * @param array $parameters An array of parameters
   */
  public function setParameters(array $parameters)
  {
    $this->parameters = array();
    foreach ($parameters as $key => $value)
    {
      $this->parameters[strtolower($key)] = $value;
    }
  }

  /**
   * Adds parameters to the service container parameters.
   *
   * @param array $parameters An array of parameters
   */
  public function addParameters(array $parameters)
  {
    $this->setParameters(array_merge($this->parameters, $parameters));
  }

  /**
   * Gets the service container parameters.
   *
   * @return array An array of parameters
   */
  public function getParameters()
  {
    return $this->parameters;
  }

  /**
   * Gets a service container parameter.
   *
   * @param  string $name The parameter name
   *
   * @return mixed  The parameter value
   *
   * @throw  InvalidArgumentException if the parameter is not defined
   */
  public function getParameter($name)
  {
    if ($this->hasParameter($name))
    {
      return $this->parameters[strtolower($name)];
    }

    if (sfConfig::has($name))
    {
      return sfConfig::get($name);
    }

    throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
  }

  /**
   * Sets a service container parameter.
   *
   * @param string $name   The parameter name
   * @param mixed  $value  The parameter value
   */
  public function setParameter($name, $value)
  {
    $this->parameters[strtolower($name)] = $value;
  }

  /**
   * Returns true if a parameter name is defined.
   *
   * @param  string  $name       The parameter name
   *
   * @return Boolean true if the parameter name is defined, false otherwise
   */
  public function hasParameter($name)
  {
    return array_key_exists(strtolower($name), $this->parameters);
  }

  /**
   * Sets a service.
   *
   * @param string $id      The service identifier
   * @param object $service The service instance
   */
  public function setService($id, $service)
  {
    $this->services[$id] = $service;
  }

  /**
   * Returns true if the given service is defined.
   *
   * @param  string  $id      The service identifier
   *
   * @return Boolean true if the service is defined, false otherwise
   */
  public function hasService($id)
  {
    return isset($this->services[$id]) || method_exists($this, 'get'.self::camelize($id).'Service');
  }

  /**
   * Gets a service.
   *
   * If a service is both defined through a setService() method and
   * with a set*Service() method, the former has always precedence.
   *
   * @param  string $id The service identifier
   *
   * @return object The associated service
   *
   * @throw InvalidArgumentException if the service is not defined
   */
  public function getService($id)
  {
    if (isset($this->services[$id]))
    {
      return $this->services[$id];
    }

    if (method_exists($this, $method = 'get'.self::camelize($id).'Service'))
    {
      return $this->$method();
    }

    throw new InvalidArgumentException(sprintf('The service "%s" does not exist.', $id));
  }

  /**
   * Gets all service ids.
   *
   * @return array An array of all defined service ids
   */
  public function getServiceIds()
  {
    $ids = array();
    $r = new ReflectionClass($this);
    foreach ($r->getMethods() as $method)
    {
      if (preg_match('/^get(.+)Service$/', $name = $method->getName(), $match))
      {
        $ids[] = self::underscore($match[1]);
      }
    }

    return array_merge($ids, array_keys($this->services));
  }

  static public function camelize($id)
  {
    return strtr(ucwords(strtr($id, array('_' => ' ', '-' => ' ', '.' => '_ '))), array(' ' => ''));
  }

  static public function underscore($id)
  {
    return strtolower(preg_replace(array('/_/', '/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('.', '\\1_\\2', '\\1_\\2'), $id));
  }
}
