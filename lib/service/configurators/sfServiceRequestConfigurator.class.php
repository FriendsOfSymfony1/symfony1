<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceRequestConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceRequestConfigurator
{
  /**
   * @var sfRouting
   */
  protected $routing;

  /**
   * sfServiceResponseConfigurator constructor.
   *
   * @param sfRouting $routing
   */
  public function __construct(sfRouting $routing)
  {
    $this->routing = $routing;
  }

  /**
   * @param sfRequest $request
   */
  public function configure(sfRequest $request)
  {
    if (!$request instanceof sfWebRequest)
    {
      return;
    }

    if (false !== ($parameters = $this->routing->parse($request->getPathInfo())))
    {
      $request->addRequestParameters($parameters);
    }
  }
}
