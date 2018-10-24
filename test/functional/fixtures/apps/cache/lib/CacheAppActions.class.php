<?php

/**
 * Application base class for all action.
 *
 * @package    project
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class CacheAppActions extends sfActions
{
  /**
   * {@inheritdoc}
   */
  public function preExecute()
  {
    $app = $this->context->getConfiguration()->getApplication();
    $statusCode = 200;

    if ('cache203' === $app) {
      $statusCode = 203;
    }

    $this->context->getResponse()->setStatusCode($statusCode);
  }
}
