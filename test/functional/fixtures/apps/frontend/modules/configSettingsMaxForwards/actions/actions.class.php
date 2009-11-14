<?php

/**
 * configSettingsMaxForwards actions.
 *
 * @package    project
 * @subpackage configSettingsMaxForwards
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class configSettingsMaxForwardsActions extends sfActions
{
  public function executeSelfForward()
  {
    $this->forward('configSettingsMaxForwards', 'selfForward');
  }
}
