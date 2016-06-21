<?php

function sc_configure($instance)
{
  $instance->configure();
}

class BarClass
{
}

class BazClass
{
  public function configure($instance)
  {
    $instance->configure();
  }

  static public function getInstance()
  {
    return new self();
  }

  static public function configureStatic($instance)
  {
    $instance->configure();
  }

  static public function configureStatic1()
  {
  }
}

class MissingObjectClass
{
  public function configure($instance)
  {

  }
}

class ClassOptionalArguments
{
  public function __construct(BarClass $bar1, BarClass $bar2 = null)
  {
  }


  public function setOptionalRegisteredObject(BarClass $bar = null) {

  }

  public function setRequiredRegisteredObject(BarClass $bar) {

  }

  public function setOptionalMissingObject(MissingObjectClass $missed = null) {

  }

  public function setRequiredMissingObject(MissingObjectClass $missed) {

  }
}
