<?php

/**
 * cache actions.
 *
 * @package    project
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class defaultActions extends CacheAppActions
{
  public function executeIndex()
  {
  }

  public function executeError404()
  {
    $this->getResponse()->setStatusCode(404);
  }
}
