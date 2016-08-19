<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryRoutingPass
   */
  class sfServiceFactoryRoutingPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (isset($config['routing']['param']['cache']['class']))
      {

        $cacheAttr          = $config['routing']['param']['cache'];
        $cacheAttr['param'] = isset($cacheAttr['param']) ? (array) $cacheAttr['param'] : array();

        $container->setDefinition('sf_routing_cache', new Definition($cacheAttr['class'], array($cacheAttr['param'])));
      }

      unset($config['routing']['param']['cache']);

      if (isset($config['routing']['class']))
      {
        $params = array_merge(
          array(
            'auto_shutdown' => false,
            'context'       => null, // this will be set later in sfServiceAppRoutingConfigurator
          ),
          sfConfig::get(
            'sf_factory_routing_parameters',
            isset($config['routing']['param']) ? (array) $config['routing']['param'] : array()
          )
        );

        $definition = new Definition(
          sfConfig::get('sf_factory_routing', $config['routing']['class']),
          array(new Reference('?sf_routing_cache'), $params)
        );

        $definition->setConfigurator(array(new Reference('sf_routing_configurator'), 'configure'));

        $container->setDefinition('sf_routing', $definition);
      }
    }
  }
}
