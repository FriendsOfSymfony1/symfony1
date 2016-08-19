<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryDatabaseManagerPass
   */
  class sfServiceFactoryDatabaseManagerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $definition = new Definition(
        $container->getParameter('sf_database_manager.class'),
        array(
          new Reference('sf_application_configuration'),
          array_merge(array('auto_shutdown' => false), $container->getParameter('sf_database_manager.param')),
        )
      );

      $container->setDefinition('sf_database_manager', $definition);
    }

  }
}
