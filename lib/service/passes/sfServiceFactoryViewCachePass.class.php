<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;

  /**
   * Class sfServiceFactoryViewCachePass
   */
  class sfServiceFactoryViewCachePass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!isset($config['view_cache']['class']))
      {
        return;
      }

      $definition = new Definition(
        sfConfig::get('sf_factory_view_cache', $config['view_cache']['class']),
        array(sfConfig::get('sf_factory_view_cache_parameters', $config['view_cache']['param']))
      );

      $container->setDefinition('sf_view_cache', $definition);
    }
  }
}
