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
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceContainer extends \Symfony\Component\DependencyInjection\Container implements sfServiceContainerInterface
{
  /**
   * BC with previous DIC implementation
   *
   * @param string $id
   *
   * @return object
   * @throws \Exception
   */
  public function getService($id)
  {
    return $this->get($id);
  }

  /**
   * BC with previous DIC implementation
   *
   * @param string $id
   *
   * @return bool
   */
  public function hasService($id)
  {
    return $this->has($id);
  }

  /**
   * BC with previous DIC implementation
   *
   * @return array
   */
  public function getParameters()
  {
    return $this->getParameterBag()->all();
  }

  /**
   * BC with previous DIC implementation
   *
   * Sets the service container parameters.
   *
   * @param array $parameters An array of parameters
   */
  public function setParameters(array $parameters)
  {
    $this->getParameterBag()->clear();
    $this->getParameterBag()->add($parameters);
  }

  /**
   * BC with previous DIC implementation
   *
   * Adds parameters to the service container parameters.
   *
   * @param array $parameters An array of parameters
   */
  public function addParameters(array $parameters)
  {
    $this->getParameterBag()->add($parameters);
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
    $name = strtolower($name);

    if ($this->hasParameter($name))
    {
      return parent::getParameter($name);
    }

    if (sfConfig::has($name))
    {
      return sfConfig::get($name);
    }

    throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
  }

  /**
   * BC with previous DIC implementation
   *
   * Sets a service.
   *
   * @param string $id      The service identifier
   * @param object $service The service instance
   */
  public function setService($id, $service)
  {
    $this->set($id, $service);
  }
}
