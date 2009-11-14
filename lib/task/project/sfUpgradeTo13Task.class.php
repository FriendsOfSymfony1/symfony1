<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Upgrade a project to the 1.3 release (from 1.2).
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfUpgradeTo13Task extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->namespace = 'project';
    $this->name = 'upgrade1.3';
    $this->briefDescription = 'Upgrade a symfony project to the 1.3 symfony release (from 1.2)';

    $this->detailedDescription = <<<EOF
The [project:upgrade1.3|INFO] task upgrades a symfony project based on the 1.2
release to the 1.3 symfony release.

  [./symfony project:upgrade1.3|INFO]

Please read the UPGRADE_TO_1_3 file to have information on what this task does.
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    foreach ($this->getUpgradeClasses() as $class)
    {
      $this->logSection('upgrade', strtolower(str_replace(array('sf', 'Upgrade'), '', $class)));

      $upgrader = new $class($this->dispatcher, $this->formatter);
      $upgrader->setCommandApplication($this->commandApplication);
      $upgrader->setConfiguration($this->configuration);
      $upgrader->upgrade();
    }

    $this->logSection('upgrade', 'complete!');
  }

  protected function getUpgradeClasses()
  {
    $baseDir = dirname(__FILE__).'/upgrade1.3/';
    $classes = array();

    foreach (glob($baseDir.'*.class.php') as $file)
    {
      $class = str_replace(array($baseDir, '.class.php'), '', $file);

      if ('sfUpgrade' != $class)
      {
        $classes[] = $class;

        require_once $baseDir.$class.'.class.php';
      }
    }

    return $classes;
  }
}
