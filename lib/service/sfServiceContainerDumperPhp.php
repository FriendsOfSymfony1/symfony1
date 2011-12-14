<?php

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * sfServiceContainerDumperPhp dumps a service container as a PHP class.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfServiceContainerDumperPhp extends sfServiceContainerDumper
{
  /**
   * Dumps the service container as a PHP class.
   *
   * Available options:
   *
   *  * class:      The class name
   *  * base_class: The base class name
   *
   * @param  array  $options An array of options
   *
   * @return string A PHP class representing of the service container
   */
  public function dump(array $options = array())
  {
    $options = array_merge(array(
      'class'      => 'ProjectServiceContainer',
      'base_class' => 'sfServiceContainer',
    ), $options);

    return
      $this->startClass($options['class'], $options['base_class']).
      $this->addConstructor().
      $this->addServices().
      $this->addDefaultParametersMethod().
      $this->endClass()
    ;
  }

  protected function addServiceInclude($id, $definition)
  {
    if (null !== $definition->getFile())
    {
      return sprintf("    require_once %s;\n\n", $this->dumpValue($definition->getFile()));
    }
  }

  protected function addServiceShared($id, $definition)
  {
    if ($definition->isShared())
    {
      return <<<EOF
    if (isset(\$this->shared['$id'])) return \$this->shared['$id'];


EOF;
    }
  }

  protected function addServiceReturn($id, $definition)
  {
    if ($definition->isShared())
    {
      return <<<EOF

    return \$this->shared['$id'] = \$instance;
  }

EOF;
    }
    else
    {
      return <<<EOF

    return \$instance;
  }

EOF;
    }
  }

  protected function addServiceInstance($id, $definition)
  {
    $class = $this->dumpValue($definition->getClass());

    $arguments = array();
    foreach ($definition->getArguments() as $value)
    {
      $arguments[] = $this->dumpValue($value);
    }

    if (null !== $definition->getConstructor())
    {
      return sprintf("    \$instance = call_user_func(array(%s, '%s')%s);\n", $class, $definition->getConstructor(), $arguments ? ', '.implode(', ', $arguments) : '');
    }
    else
    {
      if ($class != "'".$definition->getClass()."'")
      {
        return sprintf("    \$class = %s;\n    \$instance = new \$class(%s);\n", $class, implode(', ', $arguments));
      }
      else
      {
        return sprintf("    \$instance = new %s(%s);\n", $definition->getClass(), implode(', ', $arguments));
      }
    }
  }

  protected function addServiceMethodCalls($id, $definition)
  {
    $calls = '';
    foreach ($definition->getMethodCalls() as $call)
    {
      $arguments = array();
      foreach ($call[1] as $value)
      {
        $arguments[] = $this->dumpValue($value);
      }

      $calls .= sprintf("    \$instance->%s(%s);\n", $call[0], implode(', ', $arguments));
    }

    return $calls;
  }

  protected function addServiceConfigurator($id, $definition)
  {
    if (!$callable = $definition->getConfigurator())
    {
      return '';
    }

    if (is_array($callable))
    {
      if (is_object($callable[0]) && $callable[0] instanceof sfServiceReference)
      {
        return sprintf("    %s->%s(\$instance);\n", $this->getServiceCall((string) $callable[0]), $callable[1]);
      }
      else
      {
        return sprintf("    call_user_func(array(%s, '%s'), \$instance);\n", $this->dumpValue($callable[0]), $callable[1]);
      }
    }
    else
    {
      return sprintf("    %s(\$instance);\n", $callable);
    }
  }

  protected function addService($id, $definition)
  {
    $name = sfServiceContainer::camelize($id);

    $code = <<<EOF

  protected function get{$name}Service()
  {

EOF;

    $code .=
      $this->addServiceInclude($id, $definition).
      $this->addServiceShared($id, $definition).
      $this->addServiceInstance($id, $definition).
      $this->addServiceMethodCalls($id, $definition).
      $this->addServiceConfigurator($id, $definition).
      $this->addServiceReturn($id, $definition)
    ;

    return $code;
  }

  protected function addServiceAlias($alias, $id)
  {
    $name = sfServiceContainer::camelize($alias);

    return <<<EOF

  protected function get{$name}Service()
  {
    return {$this->getServiceCall($id)};
  }

EOF;
  }

  protected function addServices()
  {
    $code = '';
    foreach ($this->container->getServiceDefinitions() as $id => $definition)
    {
      $code .= $this->addService($id, $definition);
    }

    foreach ($this->container->getAliases() as $alias => $id)
    {
      $code .= $this->addServiceAlias($alias, $id);
    }

    return $code;
  }

  protected function startClass($class, $baseClass)
  {
    return <<<EOF
<?php

class $class extends $baseClass
{
  protected \$shared = array();

EOF;
  }

  protected function addConstructor()
  {
    if (!$this->container->getParameters())
    {
      return '';
    }

    return <<<EOF

  public function __construct()
  {
    parent::__construct(\$this->getDefaultParameters());
  }

EOF;
  }

  protected function addDefaultParametersMethod()
  {
    if (!$this->container->getParameters())
    {
      return '';
    }

    $parameters = $this->exportParameters($this->container->getParameters());

    return <<<EOF

  protected function getDefaultParameters()
  {
    return $parameters;
  }

EOF;
  }

  protected function exportParameters($parameters, $indent = 6)
  {
    $php = array();
    foreach ($parameters as $key => $value)
    {
      if (is_array($value))
      {
        $value = $this->exportParameters($value, $indent + 2);
      }
      elseif ($value instanceof sfServiceReference)
      {
        $value = sprintf("new sfServiceReference('%s')", $value);
      }
      elseif ($value instanceof sfServiceParameter)
      {
        $value = sprintf("\$this->getParameter('%s')", $value);
      }
      else
      {
        $value = var_export($value, true);
      }

      $php[] = sprintf('%s%s => %s,', str_repeat(' ', $indent), var_export($key, true), $value);
    }

    return sprintf("array(\n%s\n%s)", implode("\n", $php), str_repeat(' ', $indent - 2));
  }

  protected function endClass()
  {
    return <<<EOF
}

EOF;
  }

  protected function dumpValue($value)
  {
    if (is_array($value))
    {
      $code = array();
      foreach ($value as $k => $v)
      {
        $code[] = sprintf("%s => %s", $this->dumpValue($k), $this->dumpValue($v));
      }

      return sprintf("array(%s)", implode(', ', $code));
    }
    elseif (is_object($value) && $value instanceof sfServiceReference)
    {
      return $this->getServiceCall((string) $value);
    }
    elseif (is_object($value) && $value instanceof sfServiceParameter)
    {
      return sprintf("\$this->getParameter('%s')", strtolower($value));
    }
    elseif (is_string($value))
    {
      if (preg_match('/^%([^%]+)%$/', $value, $match))
      {
        // we do this to deal with non string values (boolean, integer, ...)
        // the preg_replace_callback converts them to strings
        return sprintf("\$this->getParameter('%s')", strtolower($match[1]));
      }
      else
      {
        $code = str_replace('%%', '%', preg_replace_callback('/(?<!%)(%)([^%]+)\1/', array($this, 'replaceParameter'), var_export($value, true)));

        // optimize string
        $code = preg_replace(array("/^''\./", "/\.''$/", "/\.''\./"), array('', '', '.'), $code);

        return $code;
      }
    }
    elseif (is_object($value) || is_resource($value))
    {
      throw new RuntimeException('Unable to dump a service container if a parameter is an object or a resource.');
    }
    else
    {
      return var_export($value, true);
    }
  }

  public function replaceParameter($match)
  {
    return sprintf("'.\$this->getParameter('%s').'", strtolower($match[2]));
  }

  protected function getServiceCall($id)
  {
    if ('service_container' == $id)
    {
      return '$this';
    }

    return sprintf('$this->getService(\'%s\')', $id);
  }
}
