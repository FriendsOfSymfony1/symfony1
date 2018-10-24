<?php

class cache_redirectionOnPreAction extends sfAction
{
  /**
   * {@inheritdoc}
   */
  public function preExecute()
  {
    $this->redirect('/cache/page');
  }

  /**
   * {@inheritdoc}
   */
  public function execute($request)
  {
  }
}
