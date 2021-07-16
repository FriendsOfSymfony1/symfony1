<?php

class cache_redirectionComponent extends sfComponent
{
  /**
   * {@inheritdoc}
   */
  public function execute($request)
  {
    $this->context->getController()->redirect('/cache/page');

    throw new sfStopException();
  }
}
