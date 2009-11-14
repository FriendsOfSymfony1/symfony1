<?php

require_once 'propel/engine/builder/om/php5/PHP5NestedSetPeerBuilder.php';

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage propel
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 * 
 * @deprecated since symfony 1.3
 */
class SfNestedSetPeerBuilder extends PHP5NestedSetPeerBuilder
{
  public function build()
  {
    $code = parent::build();
    if (!$this->getBuildProperty('builderAddComments'))
    {
      $code = sfToolkit::stripComments($code);
    }

    return $code;
  }
}
