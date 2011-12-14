<?php

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * sfServiceContainerLoader is the abstract class used by all built-in loaders.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfServiceContainerLoader.php 267 2009-03-26 19:56:18Z fabien $
 */
abstract class sfServiceContainerLoader implements sfServiceContainerLoaderInterface
{
  protected $container;

  /**
   * Constructor.
   *
   * @param sfServiceContainerBuilder $container A sfServiceContainerBuilder instance
   */
  public function __construct(sfServiceContainerBuilder $container = null)
  {
    $this->container = $container;
  }

  /**
   * Sets the service container attached to this loader.
   *
   * @param sfServiceContainerBuilder $container A sfServiceContainerBuilder instance
   */
  public function setServiceContainer(sfServiceContainerBuilder $container)
  {
    $this->container = $container;
  }

  /**
   * Loads a resource.
   *
   * A resource can be anything that can be converted to an array of
   * definitions and parameters by the doLoad() method.
   *
   * Service definitions overrides the current defined ones.
   *
   * But for parameters, they are overridden by the current ones. It allows
   * the parameters passed to the container constructor to have precedence
   * over the loaded ones.
   *
   * $container = new sfServiceContainerBuilder(array('foo' => 'bar'));
   * $loader = new sfServiceContainerLoaderXXX($container);
   * $loader->load('resource_name');
   * $container->register('foo', new stdClass());
   *
   * In the above example, even if the loaded resource defines a foo
   * parameter, the value will still be 'bar' as defined in the builder
   * constructor.
   *
   * You can also pass multiple resource paths to the constructor.
   *
   * @param mixed $resource The resource path
   */
  public function load($resource)
  {
    if (!$this->container)
    {
      throw new LogicException('You must attach the loader to a service container.');
    }

    $resources = func_get_args();
    foreach ($resources as $resource)
    {
      list($definitions, $parameters) = $this->doLoad($resource);

      foreach ($definitions as $id => $definition)
      {
        if (is_string($definition))
        {
          $this->container->setAlias($id, $definition);
        }
        else
        {
          $this->container->setServiceDefinition($id, $definition);
        }
      }

      $currentParameters = $this->container->getParameters();
      foreach ($parameters as $key => $value)
      {
        $this->container->setParameter($key, $this->container->resolveValue($value));
      }
      $this->container->addParameters($currentParameters);
    }
  }

  /**
   * Loads a resource.
   *
   * Concrete classes implements this method to convert
   * the resource to an array of definitions and parameters.
   *
   * @param  mixed $resource The resource path
   *
   * @return array An array of definitions and parameters
   */
  abstract public function doLoad($resource);
}
