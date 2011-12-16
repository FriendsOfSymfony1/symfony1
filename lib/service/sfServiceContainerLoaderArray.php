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
 * sfServiceContainerLoaderPhp loads an array service definitions.
 *
 * The format is the same as Yaml format, but in PHP.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Jerome Tamarelle <jtamarelle@groupe-exp.com>
 * @version    SVN: $Id$
 */
class sfServiceContainerLoaderArray extends sfServiceContainerLoaderFileYaml
{
  public function doLoad($data)
  {
    // Imported files are using sfServiceContainerLoaderFileYaml by default.
    if (isset($data[0]))
    {
      return parent::doLoad($data);
    }

    return $this->parse(array($data));
  }
}
