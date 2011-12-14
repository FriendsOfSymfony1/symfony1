<?php

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * sfServiceContainerLoaderFileXml loads XML files service definitions.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfServiceContainerLoaderFileXml.php 267 2009-03-26 19:56:18Z fabien $
 */
class sfServiceContainerLoaderFileXml extends sfServiceContainerLoaderFile
{
  /**
   * Loads an array of XML files.
   *
   * If multiple files are loaded, the services and parameters are merged.
   *
   * Remember that services and parameters are simple key/pair stores.
   *
   * When overriding a value, the old one is totally replaced, even if it is
   * a "complex" value (an array for instance):
   *
   * <pre>
   *   file1.xml
   *   <parameter key="complex" type="collection">
   *     <parameter>true</parameter>
   *     <parameter>false</parameter>
   *   </parameter>
   *
   *   file2.xml
   *   <parameter key="complex">foo</parameter>
   * </pre>
   *
   * If you load file1.xml and file2.xml in this order, the value of complex
   * will be "foo".
   *
   * @param  array $files An array of XML files
   *
   * @return array An array of definitions and parameters
   */
  public function doLoad($files)
  {
    return $this->parse($this->getFilesAsXml($files));
  }

  protected function parse(array $xmls)
  {
    $parameters = array();
    $definitions = array();

    foreach ($xmls as $file => $xml)
    {
      // create all the anonymous services and give them unique names
      list($anonymousDefinitions, $xml) = $this->processAnonymousServices($xml, $file);
      $definitions = array_merge($definitions, $anonymousDefinitions);

      // imports
      list($importedDefinitions, $importedParameters) = $this->parseImports($xml, $file);
      $definitions = array_merge($definitions, $importedDefinitions);
      $parameters = array_merge($parameters, $importedParameters);

      // parameters
      $parameters = array_merge($parameters, $this->parseParameters($xml, $file));

      // services
      $definitions = array_merge($definitions, $this->parseDefinitions($xml, $file));
    }

    return array($definitions, $parameters);
  }

  protected function parseParameters($xml, $file)
  {
    if (!$xml->parameters)
    {
      return array();
    }

    return $xml->parameters->getArgumentsAsPhp('parameter');
  }

  protected function parseImports($xml, $file)
  {
    if (!$xml->imports)
    {
      return array(array(), array());
    }

    $definitions = array();
    $parameters = array();
    foreach ($xml->imports->import as $import)
    {
      list($importedDefinitions, $importedParameters) = $this->parseImport($import, $file);

      $definitions = array_merge($definitions, $importedDefinitions);
      $parameters = array_merge($parameters, $importedParameters);
    }

    return array($definitions, $parameters);
  }

  protected function parseImport($import, $file)
  {
    if (isset($import['class']) && $import['class'] != get_class($this))
    {
      $class = (string) $import['class'];
      $loader = new $class($this->container, $this->paths);
    }
    else
    {
      $loader = $this;
    }

    $importedFile = $this->getAbsolutePath((string) $import['resource'], dirname($file));

    return call_user_func(array($loader, 'doLoad'), array($importedFile));
  }

  protected function parseDefinitions($xml, $file)
  {
    if (!$xml->services)
    {
      return array();
    }

    $definitions = array();
    foreach ($xml->services->service as $service)
    {
      $definitions[(string) $service['id']] = $this->parseDefinition($service, $file);
    }

    return $definitions;
  }

  protected function parseDefinition($service, $file)
  {
    if ((string) $service['alias'])
    {
      return (string) $service['alias'];
    }

    $definition = new sfServiceDefinition((string) $service['class']);

    foreach (array('shared', 'constructor') as $key)
    {
      $method = 'set'.ucfirst($key);
      if (isset($service[$key]))
      {
        $definition->$method((string) $service->getAttributeAsPhp($key));
      }
    }

    if ($service->file)
    {
      $definition->setFile((string) $service->file);
    }

    $definition->setArguments($service->getArgumentsAsPhp('argument'));

    if (isset($service->configurator))
    {
      if (isset($service->configurator['function']))
      {
        $definition->setConfigurator((string) $service->configurator['function']);
      }
      else
      {
        if (isset($service->configurator['service']))
        {
          $class = new sfServiceReference((string) $service->configurator['service']);
        }
        else
        {
          $class = (string) $service->configurator['class'];
        }

        $definition->setConfigurator(array($class, (string) $service->configurator['method']));
      }
    }

    foreach ($service->call as $call)
    {
      $definition->addMethodCall((string) $call['method'], $call->getArgumentsAsPhp('argument'));
    }

    return $definition;
  }

  protected function getFilesAsXml(array $files)
  {
    $xmls = array();
    foreach ($files as $file)
    {
      $path = $this->getAbsolutePath($file);

      if (!file_exists($path))
      {
        throw new InvalidArgumentException(sprintf('The service file "%s" does not exist.', $file));
      }

      $dom = new DOMDocument();
      libxml_use_internal_errors(true);
      if (!$dom->load($path))
      {
        throw new InvalidArgumentException(implode("\n", $this->getXmlErrors()));
      }
      libxml_use_internal_errors(false);
      $this->validate($dom);

      $xmls[$path] = simplexml_import_dom($dom, 'sfServiceSimpleXMLElement');
    }

    return $xmls;
  }

  protected function processAnonymousServices($xml, $file)
  {
    $definitions = array();
    $count = 0;

    // find anonymous service definitions
    $xml->registerXPathNamespace('container', 'http://symfony-project.org/2.0/container');
    $nodes = $xml->xpath('//container:argument[@type="service"][not(@id)]');
    foreach ($nodes as $node)
    {
      $node['id'] = sprintf('_%s_%d', md5($file), ++$count);
      $definitions[(string) $node['id']] = array($node->service, $file);
      $node->service['id'] = (string) $node['id'];
    }

    // resolve definitions
    krsort($definitions);
    foreach ($definitions as $id => $def)
    {
      $definitions[$id] = $this->parseDefinition($def[0], $def[1]);

      $oNode = dom_import_simplexml($def[0]);
      $oNode->parentNode->removeChild($oNode);
    }

    return array($definitions, $xml);
  }

  protected function validate($dom)
  {
    libxml_use_internal_errors(true);
    if (!$dom->schemaValidate(dirname(__FILE__).'/services.xsd'))
    {
      throw new InvalidArgumentException(implode("\n", $this->getXmlErrors()));
    }
    libxml_use_internal_errors(false);
  }

  protected function getXmlErrors()
  {
    $errors = array();
    foreach (libxml_get_errors() as $error)
    {
      $errors[] = sprintf('[%s %s] %s (in %s - line %d, column %d)',
        LIBXML_ERR_WARNING == $error->level ? 'WARNING' : 'ERROR',
        $error->code,
        trim($error->message),
        $error->file ? $error->file : 'n/a',
        $error->line,
        $error->column
      );
    }

    libxml_clear_errors();

    return $errors;
  }
}
