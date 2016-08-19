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
interface sfServiceContainerInterface extends \Symfony\Component\DependencyInjection\ContainerInterface
{
  /**
   * @param array $parameters
   *
   * @return void
   */
  public function setParameters(array $parameters);

  /**
   * @param array $parameters
   *
   * @return void
   */
  public function addParameters(array $parameters);

  /**
   * @return array
   */
  public function getParameters();

  /**
   * @param string $id
   * @param mixed $service
   *
   * @return void
   */
  public function setService($id, $service);

  /**
   * @param string $id
   *
   * @return mixed
   */
  public function getService($id);

  /**
   * @param string $name
   *
   * @return boolean
   */
  public function hasService($name);
}
