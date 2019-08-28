<?php


class autowiringActions extends sfActions
{
  public function executeIndex($request, MyService $service)
  {
    $this->value = $service->execute();
    return sfView::SUCCESS;
  }

  public function executeWithComponents($request)
  {
    return sfView::SUCCESS;
  }
}
