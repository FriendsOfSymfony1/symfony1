<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfRenderingFilter is the last filter registered for each filter chain. This
 * filter does the rendering.
 *
 * @package    symfony
 * @subpackage filter
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfRenderingFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain The filter chain.
   *
   * @throws <b>sfInitializeException</b> If an error occurs during view initialization
   * @throws <b>sfViewException</b>       If an error occurs while executing the view
   */
  public function execute($filterChain)
  {
    $controller = $this->context->getController();
    $exception = null;

    // execute next filter
    try
    {
      $filterChain->execute();
    }
    catch (sfStopException $exception)
    {
      // Send the response when stop the execution for a redirection.
      if (sfView::RENDER_REDIRECTION !== $controller->getRenderMode())
      {
        throw $exception;
      }
    }

    // get response object
    $response = $this->context->getResponse();

    // hack to rethrow sfForm and|or sfFormField __toString() exceptions (see sfForm and sfFormField)
    if (sfForm::hasToStringException())
    {
      throw sfForm::getToStringException();
    }
    else if (sfFormField::hasToStringException())
    {
      throw sfFormField::getToStringException();
    }

    // send headers + content
    if (sfView::RENDER_VAR != $controller->getRenderMode())
    {
      $response->send();
    }

    // Re-throw the exception to keep the encapsulation.
    if (null !== $exception)
    {
      throw $exception;
    }
  }
}
