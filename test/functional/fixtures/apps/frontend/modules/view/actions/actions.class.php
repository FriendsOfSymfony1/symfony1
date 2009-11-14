<?php

/**
 * view actions.
 *
 * @package    project
 * @subpackage view
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class viewActions extends sfActions
{
  public function executeIndex()
  {
    $this->setTemplate('foo');
  }

  public function executePlain()
  {
  }

  public function executeImage()
  {
  }
}
