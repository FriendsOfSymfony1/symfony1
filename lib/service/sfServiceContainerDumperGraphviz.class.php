<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceContainerDumperGraphviz dumps a service container as a graphviz file.
 *
 * You can convert the generated dot file with the dot utility (http://www.graphviz.org/):
 *
 *   dot -Tpng container.dot > foo.png
 *
 * @package    symfony
 * @subpackage service
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfServiceContainerDumperGraphviz extends sfServiceContainerDumper
{
  protected $nodes, $edges, $options;

  /**
   * Dumps the service container as a graphviz graph.
   *
   * Available options:
   *
   *  * graph: The default options for the whole graph
   *  * node: The default options for nodes
   *  * edge: The default options for edges
   *  * node.instance: The default options for services that are defined directly by object instances
   *  * node.definition: The default options for services that are defined via service definition instances
   *  * node.missing: The default options for missing services
   *
   * @param  array  $options An array of options
   *
   * @return string The dot representation of the service container
   */
  public function dump(array $options = array())
  {
    $this->options = array(
      'graph' => array('ratio' => 'compress'),
      'node'  => array('fontsize' => 11, 'fontname' => 'Arial', 'shape' => 'record'),
      'edge'  => array('fontsize' => 9, 'fontname' => 'Arial', 'color' => 'grey', 'arrowhead' => 'open', 'arrowsize' => 0.5),
      'node.instance' => array('fillcolor' => '#9999ff', 'style' => 'filled'),
      'node.definition' => array('fillcolor' => '#eeeeee'),
      'node.missing' => array('fillcolor' => '#ff9999', 'style' => 'filled'),
    );

    foreach (array('graph', 'node', 'edge', 'node.instance', 'node.definition', 'node.missing') as $key)
    {
      if (isset($options[$key]))
      {
        $this->options[$key] = array_merge($this->options[$key], $options[$key]);
      }
    }

    $this->nodes = $this->findNodes();

    $this->edges = array();
    foreach ($this->container->getServiceDefinitions() as $id => $definition)
    {
      $this->edges[$id] = $this->findEdges($id, $definition->getArguments(), '');

      foreach ($definition->getMethodCalls() as $call)
      {
        $this->edges[$id] = array_merge(
          $this->edges[$id],
          $this->findEdges($id, $call[1], $call[0].'()')
        );
      }
    }

    return $this->startDot().$this->addNodes().$this->addEdges().$this->endDot();
  }

  protected function addNodes()
  {
    $code = '';
    foreach ($this->nodes as $id => $node)
    {
      $aliases = $this->getAliases($id);

      $code .= sprintf("  node_%s [label=\"%s\\n%s\\n\", shape=%s%s];\n", $this->dotize($id), $id.($aliases ? ' ('.implode(', ', $aliases).')' : ''), $node['class'], $this->options['node']['shape'], $this->addAttributes($node['attributes']));
    }

    return $code;
  }

  protected function addEdges()
  {
    $code = '';
    foreach ($this->edges as $id => $edges)
    {
      foreach ($edges as $edge)
      {
        $code .= sprintf("  node_%s -> node_%s [label=\"%s\" style=\"%s\"];\n", $this->dotize($id), $this->dotize($edge['to']), $edge['name'], $edge['required'] ? 'filled' : 'dashed');
      }
    }

    return $code;
  }

  protected function findEdges($id, $arguments, $name)
  {
    $edges = array();
    foreach ($arguments as $argument)
    {
      $required = true;
      if (is_object($argument) && $argument instanceof sfServiceParameter)
      {
        $argument = $this->container->hasParameter($argument) ? $this->container->getParameter($argument) : null;
      }
      elseif (is_string($argument) && preg_match('/^%([^%]+)%$/', $argument, $match))
      {
        $argument = $this->container->hasParameter($match[1]) ? $this->container->getParameter($match[1]) : null;
      }

      if ($argument instanceof sfServiceReference)
      {
        $serviceId = (string) $argument;

        if (0 === strpos($serviceId, '?'))
        {
          // Mark optional (starts with "?") services as non-required
          $serviceId = substr($serviceId, 1);
          $argument = new sfServiceReference($serviceId);
          $required = false;
        }

        if (!$this->container->hasService($serviceId))
        {
          $this->nodes[$serviceId] = array(
            'name' => $name,
            'required' => $required,
            'class' => '',
            'attributes' => $this->options['node.missing']
          );
        }

        $edges[] = array('name' => $name, 'required' => $required, 'to' => $argument);
      }
      elseif (is_array($argument))
      {
        $edges = array_merge($edges, $this->findEdges($id, $argument, $name));
      }
    }

    return $edges;
  }

  protected function findNodes()
  {
    $nodes = array();

    $container = clone $this->container;

    foreach ($container->getServiceDefinitions() as $id => $definition)
    {
      $nodes[$id] = array('class' => $this->getValue($definition->getClass()), 'attributes' => array_merge($this->options['node.definition'], array('style' => $definition->isShared() ? 'filled' : 'dotted')));

      $container->setServiceDefinition($id, new sfServiceDefinition('stdClass'));
    }

    foreach ($container->getServiceIds() as $id)
    {
      if (array_key_exists($id, $container->getAliases()))
      {
        continue;
      }

      if (!$container->hasServiceDefinition($id))
      {
        $nodes[$id] = array('class' => get_class($container->getService($id)), 'attributes' => $this->options['node.instance']);
      }
    }

    return $nodes;
  }

  protected function getValue($value, $default = '')
  {
    return $this->container->resolveValue($value);
  }

  protected function startDot()
  {
    $parameters = var_export($this->container->getParameters(), true);

    return sprintf("digraph sc {\n  %s\n  node [%s];\n  edge [%s];\n\n",
      $this->addOptions($this->options['graph']),
      $this->addOptions($this->options['node']),
      $this->addOptions($this->options['edge'])
    );
  }

  protected function endDot()
  {
    return "}\n";
  }

  protected function addAttributes($attributes)
  {
    $code = array();
    foreach ($attributes as $k => $v)
    {
      $code[] = sprintf('%s="%s"', $k, $v);
    }

    return $code ? ', '.implode(', ', $code) : '';
  }

  protected function addOptions($options)
  {
    $code = array();
    foreach ($options as $k => $v)
    {
      $code[] = sprintf('%s="%s"', $k, $v);
    }

    return implode(' ', $code);
  }

  protected function dotize($id)
  {
    return strtolower(preg_replace('/\W/i', '_', $id));
  }

  protected function getAliases($id)
  {
    $aliases = array();
    foreach ($this->container->getAliases() as $alias => $origin)
    {
      if ($id == $origin)
      {
        $aliases[] = $alias;
      }
    }

    return $aliases;
  }
}
