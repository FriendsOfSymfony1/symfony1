<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Renders a service container dependencies into DOT file
 *
 * @package    symfony
 * @subpackage task
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServicesGraphvizDumpTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(
      array(
        new sfCommandOption(
          'application',
          null,
          sfCommandOption::PARAMETER_REQUIRED,
          'The application name',
          'frontend'
        ),
        // add your own options here
      )
    );

    $this->namespace = 'services';
    $this->name = 'graphviz';
    $this->briefDescription = 'Creates a Graphviz (.dot) file with the container dependencies';
    $this->detailedDescription = <<<EOF
Usage:

    [./symfony {$this->namespace}:{$this->name}|INFO]
EOF;
  }

  /**
   * @param array $arguments
   * @param array $options
   *
   * @return int
   * @throws \sfFactoryException
   */
  protected function execute($arguments = array(), $options = array())
  {
    $configFiles = $this->configuration->getConfigPaths('config/services.yml');

    $serviceContainerBuilder = new sfServiceContainerBuilder();
    $loader = new sfServiceContainerLoaderArray($serviceContainerBuilder);
    $loader->load(sfServiceConfigHandler::getConfiguration($configFiles));

    $dumper = new sfServiceContainerDumperGraphviz($serviceContainerBuilder);

    $dumpDir = sfConfig::get('sf_cache_dir') . '/service-container';

    if (!is_dir($dumpDir))
    {
      if (!mkdir($dumpDir, 0777, true))
      {
        $this->logBlock(sprintf('Could not create temporary dir "%s"', $dumpDir), 'ERROR');
        return 1;
      }
    }
    $dotFile = sprintf('%s/%d-graphviz.dot', $dumpDir, time());

    if (!file_put_contents($dotFile, $dumper->dump()))
    {
      $this->logBlock(sprintf('Could write content into file "%s"', $dotFile), 'ERROR');
      return 2;
    }

    $this->logSection('+file', sprintf('Graphviz file created at %s', $dotFile));

    return 0;
  }
}
