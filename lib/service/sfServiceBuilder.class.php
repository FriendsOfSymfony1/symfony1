<?php

namespace {

  /*
   * This file is part of the symfony package.
   * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
   *
   * For the full copyright and license information, please view the LICENSE
   * file that was distributed with this source code.
   */
  use Symfony\Component\Config\FileLocator;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
  use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
  use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

  /**
   * Class sfServiceBuilder
   *
   * @package    symfony
   * @subpackage service
   * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
   * @version    SVN: $Id$
   */
  class sfServiceBuilder
  {
    /**
     * @var sfEventDispatcher
     */
    protected $dispatcher;

    /**
     * @var sfProjectConfiguration
     */
    protected $configuration;

    /**
     * sfServiceBuilder constructor.
     *
     * @param sfEventDispatcher      $dispatcher
     * @param sfProjectConfiguration $configuration
     */
    public function __construct(sfEventDispatcher $dispatcher, sfProjectConfiguration $configuration)
    {
      $this->dispatcher    = $dispatcher;
      $this->configuration = $configuration;
    }

    /**
     * @param string $className
     * @param string $configBasename
     *
     * @return sfServiceContainer
     */
    public function create($className, $configBasename)
    {
      list($containerFile, $containerClass) = $this->build($className, $configBasename);

      /** @noinspection PhpIncludeInspection */
      include_once $containerFile;
      $container = new $containerClass();

      $this->dispatcher->notify(
        new sfEvent(
          $container,
          'service_container.created',
          array(
            'configuration'  => $this->configuration,
            'className'      => $containerClass,
            'configBasename' => $configBasename,
          )
        )
      );

      return $container;
    }

    /**
     * @param string $className
     * @param string $configBasename
     *
     * @return array
     * @throws sfConfigurationException
     */
    public function build($className, $configBasename)
    {
      $containerFile  = sfConfig::get('sf_config_cache_dir') . "/{$className}.php";
      $dumpOptions    = array_merge(sfConfig::get('sf_container_options', array()), array('class' => $className));
      $containerClass = isset($dumpOptions['namespace']) ? "{$dumpOptions['namespace']}\\{$className}" : $className;

      if (sfConfig::get('sf_debug') || !is_file($containerFile))
      {
        $container = new ContainerBuilder(new ParameterBag(sfConfig::getAll()));

        $event = new sfEvent(
          $container,
          'service_container.initialized',
          array(
            'configuration'  => $this->configuration,
            'className'      => $containerClass,
            'configBasename' => $configBasename,
          )
        );
        $this->dispatcher->notify($event);

        $loader = new YamlFileLoader(
          $container, new FileLocator(sfConfig::get('sf_symfony_lib_dir') . '/config/config')
        );
        $loader->load($configBasename);

        try
        {
          $loader = new YamlFileLoader($container, new FileLocator(sfConfig::get('sf_config_dir')));
          $loader->load($configBasename);
        }
        catch (\InvalidArgumentException $e)
        {

        }

        $factoryConfig = $this->getFactoryConfig();

        //'view_cache_manager<->view_cache', 'logger+', 'i18n+', 'controller+', 'request+', 'response+', 'routing+', 'storage+', 'user+', 'view_cache+', 'mailer+');

        $event = new sfEvent(
          $container,
          'service_container.before_compile',
          array(
            'configuration'  => $this->configuration,
            'className'      => $containerClass,
            'configBasename' => $configBasename,
            'factoryConfig'  => $factoryConfig,
          )
        );

        $this->dispatcher->notify($event);

        $container->compile();

        $dumper  = new PhpDumper($container);
        $content = $dumper->dump($dumpOptions);

        if (!file_put_contents($containerFile, $content))
        {
          throw new sfConfigurationException(sprintf('File "%s" could not be written', $containerFile));
        }
      }

      return array($containerFile, $containerClass);
    }

    /**
     * @return array
     */
    protected function getFactoryConfig()
    {
      return sfYamlConfigHandler::flattenConfigurationWithEnvironment(
        sfYamlConfigHandler::parseYamls($this->configuration->getConfigPaths('config/factories.yml'))
      );
    }
  }
}
