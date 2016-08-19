<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceAppRoutingConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceAppRoutingConfigurator
{
  /**
   * @var sfRequest
   */
  protected $request;

  /**
   * sfServiceTaskRoutingConfigurator constructor.
   *
   * @param sfRequest $request
   */
  public function __construct(sfRequest $request)
  {
    $this->request = $request;
  }

  /**
   * @param sfPatternRouting $routing
   */
  public function configure(sfPatternRouting $routing)
  {
    if ($this->request instanceof sfWebRequest)
    {
      $routing->setOption('context', $this->request->getRequestContext());
    }
  }
}
