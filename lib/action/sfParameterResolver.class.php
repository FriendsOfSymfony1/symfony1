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

      if (null === $type) {
        if ($i === 0) {
          // first parameter is always the request
          $parameters[] = $this->request;
          continue;
        } elseif ($param->getDefaultValue()) {
          // additional params with default values may have been added
          $params[] = $param->getDefaultValue();
          continue;
        } else {
          throw new \Exception("Additional parameters must be type hinted or provide a default value");
        }
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
