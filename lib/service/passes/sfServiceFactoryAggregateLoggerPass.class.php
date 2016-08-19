<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryAggregateLoggerPass
   */
  class sfServiceFactoryAggregateLoggerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param ContainerBuilder $container
     *
     * @throws \sfParseException
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!isset($config['logger']['class']))
      {
        return;
      }

      $mainLoggerParams = isset($config['logger']['param']) ? (array) $config['logger']['param'] : array();
      if (isset($mainLoggerParams['loggers']) && is_array($mainLoggerParams['loggers']))
      {
        foreach ($mainLoggerParams['loggers'] as $loggerName => $loggerAttr)
        {
          // Based on
          if (isset($loggerAttr['enabled']) && !$loggerAttr['enabled'])
          {
            continue;
          }

          if (!isset($loggerAttr['class']))
          {
            // missing class key
            throw new sfParseException(
              sprintf('Configuration file specifies logger "%s" with missing class key.', $loggerName)
            );
          }

          $condition = true;
          if (isset($loggerAttr['param']['condition']))
          {
            $condition = $loggerAttr['param']['condition'];
            unset($loggerAttr['param']['condition']);
          }

          if (!$condition)
          {
            continue;
          }

          $params = array_merge(
            array('auto_shutdown' => false),
            isset($loggerAttr['param']) ? (array) $loggerAttr['param'] : array()
          );

          $definition = new Definition($loggerAttr['class'], array(new Reference('sf_event_dispatcher'), $params));
          $definition->addTag('sf.logger', array('name' => 'sf.logger'));

          $container->setDefinition($loggerName, $definition);
        }
      }

      unset($mainLoggerParams['loggers']);
      $params = array_merge(
        array('auto_shutdown' => false),
        sfConfig::get('sf_factory_logger_parameters', $mainLoggerParams)
      );

      $definition = new Definition(
        sfConfig::get('sf_factory_logger', $config['logger']['class']),
        array(new Reference('sf_event_dispatcher'), $params)
      );
      $container->setDefinition('sf_logger', $definition);
    }
  }
}
