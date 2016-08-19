<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryViewCacheManagerPass
   */
  class sfServiceFactoryViewCacheManagerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      if (!sfConfig::get('sf_cache'))
      {
        return;
      }

      $config = $this->factoryConfig;

      if (!isset($config['view_cache_manager']['class']))
      {
        return;
      }

      $classAttr = $config['view_cache_manager'];

      $definition = new Definition(
        sfConfig::get('sf_factory_view_cache_manager', $classAttr['class']),
        array(
          new Reference('sf_context'),
          new Reference('sf_view_cache'),
          $classAttr['param'],
        )
      );

      $container->setDefinition('sf_view_cache_manager', $definition);
    }
  }
}
