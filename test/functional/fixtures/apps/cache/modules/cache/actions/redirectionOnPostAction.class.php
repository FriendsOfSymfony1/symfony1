<?php

class cache_redirectionOnPostAction extends sfAction
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
