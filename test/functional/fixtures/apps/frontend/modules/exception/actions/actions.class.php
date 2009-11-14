<?php

/**
 * exception actions.
 *
 * @package    project
 * @subpackage exception
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class exceptionActions extends sfActions
{
  public function executeNoException()
  {
    return $this->renderText('foo');
  }

  public function executeThrowsException()
  {
    throw new Exception('Exception message');
  }

  public function executeThrowsSfException()
  {
    throw new sfException('sfException message');
  }
}
