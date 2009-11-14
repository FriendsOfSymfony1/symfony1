<?php

/**
 * article actions.
 *
 * @package    project
 * @subpackage article
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class articleActions extends autoarticleActions
{
  public function executeMyAction()
  {
    return $this->renderText('Selected '.implode(', ', $this->getRequestParameter('sf_admin_batch_selection', array())));
  }
}
