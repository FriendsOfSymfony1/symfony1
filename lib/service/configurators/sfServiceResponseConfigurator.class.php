<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceResponseConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceResponseConfigurator
{
  /**
   * @var sfRequest
   */
  protected $request;

  /**
   * sfServiceResponseConfigurator constructor.
   *
   * @param sfRequest $request
   */
  public function __construct(sfRequest $request)
  {
    $this->request = $request;
  }

  /**
   * @param sfResponse $response
   */
  public function configure(sfResponse $response)
  {
    if (!$this->request instanceof sfWebRequest)
    {
      return;
    }

    if (!$response instanceof sfWebResponse)
    {
      return;
    }

    if (!$this->request->isMethod(sfRequest::HEAD))
    {
      return;
    }

    $response->setHeaderOnly(true);
  }
}
