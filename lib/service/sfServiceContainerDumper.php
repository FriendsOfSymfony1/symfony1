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
 * sfServiceContainerDumper is the abstract class for all built-in dumpers.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
abstract class sfServiceContainerDumper implements sfServiceContainerDumperInterface
{
  protected $container;

  /**
   * Constructor.
   *
   * @param sfServiceContainerBuilder $container The service container to dump
   */
  public function __construct(sfServiceContainerBuilder $container)
  {
    $this->container = $container;
  }

  /**
   * Dumps the service container.
   *
   * @param  array  $options An array of options
   *
   * @return string The representation of the service container
   */
  public function dump(array $options = array())
  {
    throw new LogicException('You must extend this abstract class and implement the dump() method.');
  }
}
