<?php

/**
 * Class sfAbstractServiceFactoryPass
 */
abstract class sfAbstractServiceFactoryPass
{
  /**
   * @var array
   */
  protected $factoryConfig = array();

  /**
   * sfAbstractServiceFactoryPass constructor.
   *
   * @param $factoryConfig
   */
  public function __construct($factoryConfig)
  {
    $this->factoryConfig = $factoryConfig;
  }
}
