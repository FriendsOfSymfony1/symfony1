<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryControllerPass
   */
  class sfServiceFactoryControllerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (isset($config['controller']['class']))
      {
        $controller = new Definition(
          sfConfig::get('sf_factory_controller', 'sfFrontWebController'),
          array(new Reference('sf_context'))
        );
        $container->setDefinition('sf_controller', $controller);
      }
    }
  }
}
