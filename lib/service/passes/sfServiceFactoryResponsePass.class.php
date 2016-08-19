<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryResponsePass
   */
  class sfServiceFactoryResponsePass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!isset($config['response']['class']))
      {
        return;
      }

      $params = array_merge(
        array(
          'http_protocol' => isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : null,
        ),
        isset($config['response']['param']) ? (array) $config['response']['param'] : array()
      );

      $definition = new Definition(
        sfConfig::get('sf_factory_response', $config['response']['class']),
        array(
          new Reference('sf_event_dispatcher'),
          sfConfig::get('sf_factory_response_parameters', $params),
        )
      );

      $definition->setConfigurator(array(new Reference('sf_response_configurator'), 'configure'));

      $container->setDefinition('sf_response', $definition);
    }
  }
}
