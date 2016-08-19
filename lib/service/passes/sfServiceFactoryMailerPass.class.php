<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryMailerPass
   */
  class sfServiceFactoryMailerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (isset($config['mailer']['class']))
      {
        $definition = new Definition(
          sfConfig::get('sf_factory_mailer', $config['mailer']['class']),
          array(
            new Reference('sf_event_dispatcher'),
            sfConfig::get('sf_factory_mailer_parameters', $config['mailer']['param']),
          )
        );

        $container->setDefinition('sf_mailer', $definition);
      }
    }

  }
}
