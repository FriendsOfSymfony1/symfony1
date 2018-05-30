<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceContainerInterface is the interface implemented by service container classes.
 *
 * @package    symfony
 * @subpackage service
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
interface sfServiceContainerInterface
{
  /**
   * Sets the service container parameters.
   * All previously defined parameters would be removed.
   *
   * @see addParameters()
   *
   * @param array $parameters An array of parameters
   */
  public function setParameters(array $parameters);

  /**
   * Adds parameters to the service container parameters.
   *
   * @param array $parameters An array of parameters
   */
  public function addParameters(array $parameters);

  /**
   * Gets the service container parameters.
   *
   * @return array An array of parameters
   */
  public function getParameters();

  /**
   * Gets a service container parameter.
   *
   * @param  string $name The parameter name
   *
   * @return mixed  The parameter value
   *
   * @throw  InvalidArgumentException if the parameter is not defined
   */
  public function getParameter($name);

  /**
   * Sets a service container parameter.
   *
   * @param string $name  The parameter name
   * @param mixed  $value The parameter value
   */
  public function setParameter($name, $value);

  /**
   * Returns true if a parameter name is defined.
   *
   * @param  string  $name       The parameter name
   *
   * @return Boolean true if the parameter name is defined, false otherwise
   */
  public function hasParameter($name);

  /**
   * Sets a service.
   *
   * @param string $id      The service identifier
   * @param object $service The service instance
   */
  public function setService($id, $service);

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
  public function getService($id);

  /**
   * Returns true if the given service is defined.
   *
   * @param  string  $id      The service identifier
   *
   * @return Boolean true if the service is defined, false otherwise
   */
  public function hasService($id);
}
