<?php


class autowiringComponents extends sfComponents
{
  public function executeComponent(MyService $service)
  {
    $this->value = $service->execute();
  }
}
