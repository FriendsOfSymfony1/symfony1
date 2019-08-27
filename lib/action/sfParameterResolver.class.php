<?php

class sfParameterResolver
{
  protected $container;
  protected $request;
  protected $component;

  public function __construct(sfServiceContainer $container = null)
  {
    $this->container = $container;
  }

  public function setRequest(sfWebRequest $request)
  {
    $this->request = $request;

    return $this;
  }

  public function setComponent(sfComponent $component)
  {
    $this->component = $component;

    return $this;
  }

  public function execute($actionToRun = 'execute')
  {
    return call_user_func_array(array($this->component, $actionToRun), $this->resolveParams($actionToRun));
  }

  protected function resolveParams($actionToRun)
  {
    $reflection = new ReflectionObject($this->component);
    $method = $reflection->getMethod($actionToRun);

    $parameters = array();
    foreach ($method->getParameters() as $i => $param) {
      $type = $param->getClass();

      // handle case where request parameter was not type hinted
      if (null === $type && $i === 0) {
        $parameters[] = $this->request;
        continue;
      }

      if (null === $type) {
        throw new \Exception("Additional parameters must be type hinted");
      }

      if ($type->getName() == "sfWebRequest") {
        $parameters[] = $this->request;
      } else {
        $parameters[] = $this->getParamFromContainer($type->getName());
      }
    }

    return $parameters;
  }

  protected function getParamFromContainer($param)
  {
    return $this->container->getService($param);
  }
}
