<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryI18nPass
   */
  class sfServiceFactoryI18nPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      if (!sfConfig::get('sf_i18n'))
      {
        return;
      }

      $config = $this->factoryConfig;

      if (isset($config['i18n']['param']['cache']['class']))
      {
        $cacheAttr          = $config['i18n']['param']['cache'];
        $cacheAttr['param'] = isset($cacheAttr['param']) ? (array) $cacheAttr['param'] : array();

        $container->setDefinition('sf_i18n_cache', new Definition($cacheAttr['class'], array($cacheAttr['param'])));
      }

      unset($config['i18n']['param']['cache']);

      if (isset($config['i18n']['class']))
      {
        $params = sfConfig::get(
          'sf_factory_i18n_parameters',
          isset($config['i18n']['param']) ? (array) $config['i18n']['param'] : array()
        );

        $definition = new Definition(
          sfConfig::get('sf_factory_i18n', $config['i18n']['class']),
          array(
            new Reference('sf_application_configuration'),
            new Reference('sf_i18n_cache'),
            $params,
          )
        );

        $definition->setConfigurator(array(new Reference('sf_i18n_configurator'), 'configure'));

        $container->setDefinition('sf_i18n', $definition);
      }
    }
  }
}
