<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceContainerDumper is the abstract class for all built-in dumpers.
 *
 * @package    symfony
 * @subpackage service
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
