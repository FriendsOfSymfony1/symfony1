<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryLoggersPass
   */
  class sfServiceFactoryLoggersPass implements CompilerPassInterface
  {
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      if (!$container->has('sf_logger'))
      {
        return;
      }

      $definition     = $container->findDefinition('sf_logger');
      $taggedServices = $container->findTaggedServiceIds('sf.logger');

      foreach ($taggedServices as $id => $tags)
      {
        $logger = $container->findDefinition($id);

        try
        {
          $options = $logger->getArgument(1);
        }
        catch (\OutOfBoundsException $e)
        {
          continue;
        }

        if (isset($options['enabled']) && !$options['enabled'])
        {
          continue;
        }

        $condition = true;
        if (isset($keys['param']['condition']))
        {
          $condition = $keys['param']['condition'];
          unset($keys['param']['condition']);
        }

        if (!$condition)
        {
          continue;
        }

        $definition->addMethodCall('addLogger', array(new Reference($id)));
      }
    }

  }
}
