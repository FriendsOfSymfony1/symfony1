<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;

  /**
   * Class sfServiceFactoryStoragePass
   */
  class sfServiceFactoryStoragePass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
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

      if (!isset($config['storage']['class']))
      {
        return;
      }

      $className = $config['storage']['class'];

      // TODO: access to missing instance "sf_request"
      $request = $container->get('sf_request');
      /* @var sfRequest $request */

      $params            = isset($config['storage']['param']) ? (array) $config['storage']['param'] : array();
      $defaultParameters = array(
        'auto_shutdown' => false,
        'session_id'    => $request->getParameter($params['session_name']), // rewrite using configurator
      );

      if (is_subclass_of($className, 'sfDatabaseSessionStorage'))
      {
        if (!sfConfig::get('sf_use_database'))
        {
          throw new \RuntimeException(sprintf('You can not use "%s" with sf_use_database=false', $className));
        }

        // TODO: access to instance "sf_database_manager" within builder
        $databaseManager = $container->get('sf_database_manager');
        /* @var sfDatabaseManager $databaseManager */

        $databaseName                  = isset($params['database']) ? $params['database'] : 'default';
        $defaultParameters['database'] = $databaseManager->getDatabase($databaseName);
        unset($params['database']);
      }

      $params = array_merge($defaultParameters, sfConfig::get('sf_factory_storage_parameters', $params));

      $definition = new Definition(sfConfig::get('sf_factory_storage', $className), array($params));

      $container->setDefinition('sf_storage', $definition);
    }

  }
}
