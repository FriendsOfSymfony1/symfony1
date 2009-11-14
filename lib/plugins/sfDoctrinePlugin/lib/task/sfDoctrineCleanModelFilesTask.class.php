<?php

/*
 * This file is part of the symfony package.
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Delete all generated model classes for models which no longer exist in your YAML schema
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 */
class sfDoctrineCleanModelFilesTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not ask for confirmation'),
    ));

    $this->aliases = array('doctrine:clean');
    $this->namespace = 'doctrine';
    $this->name = 'clean-model-files';
    $this->briefDescription = 'Delete all generated model classes for models which no longer exist in your YAML schema';

    $this->detailedDescription = <<<EOF
The [doctrine:clean-model-files|INFO] task deletes model classes that are not
represented in project or plugin schema.yml files:

  [./symfony doctrine:clean-model-files|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $config = $this->getCliConfig();

    if ($modelsToRemove = array_diff($this->getFileModels($config['models_path']), $this->getYamlModels($config['yaml_schema_path'])))
    {
      $deleteModelFiles = new sfDoctrineDeleteModelFilesTask($this->dispatcher, $this->formatter);
      $deleteModelFiles->setCommandApplication($this->commandApplication);
      $deleteModelFiles->setConfiguration($this->configuration);
      $deleteModelFiles->run($modelsToRemove, array('no-confirmation' => $options['no-confirmation']));

      $this->reloadAutoload();
    }
    else
    {
      $this->logSection('doctrine', 'Could not find any models that need to be removed');
    }
  }

  /**
   * Returns models defined in YAML.
   * 
   * @return array
   */
  protected function getYamlModels($yamlSchemaPath)
  {
    $schema = (array) sfYaml::load($this->prepareSchemaFile($yamlSchemaPath));
    return array_keys($schema);
  }

  /**
   * Returns models that have class files.
   * 
   * @return array
   */
  protected function getFileModels($modelsPath)
  {
    Doctrine_Core::loadModels($modelsPath);
    return Doctrine_Core::getLoadedModels();
  }
}
