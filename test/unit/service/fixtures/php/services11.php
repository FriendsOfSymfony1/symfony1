class ProjectServiceContainer extends sfServiceContainer
{
  protected $shared = array();

  protected function getBarService()
  {
    if (isset($this->shared['bar'])) return $this->shared['bar'];

    $instance = new BarClass();

    return $this->shared['bar'] = $instance;
  }

  protected function getDemoService()
  {
    if (isset($this->shared['demo'])) return $this->shared['demo'];

    $instance = new ClassOptionalArguments($this->getService('bar'), (! $this->hasService('bar') ? null : $this->getService('bar')));
    $instance->setOptionalRegisteredObject((! $this->hasService('bar') ? null : $this->getService('bar')));
    $instance->setRequiredRegisteredObject($this->getService('bar'));
    $instance->setOptionalMissingObject((! $this->hasService('missing_bar') ? null : $this->getService('missing_bar')));
    $instance->setRequiredMissingObject($this->getService('missing_bar'));
    $configurator = (! $this->hasService('missing_bar') ? null : $this->getService('missing_bar'));
    if (null !== $configurator)
    {
      $configurator->configure($instance);
    }

    return $this->shared['demo'] = $instance;
  }
}
