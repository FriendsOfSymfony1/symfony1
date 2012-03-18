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
  public function setParameters(array $parameters);

  public function addParameters(array $parameters);

  public function getParameters();

  public function getParameter($name);

  public function setParameter($name, $value);

  public function hasParameter($name);

  public function setService($id, $service);

  public function getService($id);

  public function hasService($name);
}
