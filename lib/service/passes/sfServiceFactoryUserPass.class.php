<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryUserPass
   */
  class sfServiceFactoryUserPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!$container->has('sf_request'))
      {
        return;
      }

      if (!isset($config['user']['class']))
      {
        return;
      }

      // TODO: access to missing instance "sf_request"
      $request = $container->get('sf_request');
      /* @var sfRequest $request */

      $params = array_merge(
        array('auto_shutdown' => false, 'culture' => $request->getParameter('sf_culture')),
        sfConfig::get('sf_factory_user_parameters', $config['user']['param'])
      );

      $definition = new Definition(
        sfConfig::get('sf_factory_user', $config['user']['class']),
        array(
          new Reference('sf_event_dispatcher'),
          new Reference('sf_storage'),
          $params,
        )
      );

      $container->setDefinition('sf_user', $definition);
    }
  }
}
