<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Finds deprecated configuration files usage.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfAssetsUpgrade.class.php 24395 2009-11-25 19:02:18Z Kris.Wallsmith $
 */
class sfDeprecatedConfigurationFilesValidation extends sfValidation
{
  public function getHeader()
  {
    return 'Checking usage of deprecated configuration files';
  }

  public function getExplanation()
  {
    return array(
          '',
          '  The project uses deprecated configuration files',
          '  that have been removed in symfony 1.4.',
          '',
    );
  }

  public function validate()
  {
    // mailer.yml
    $files = sfFinder::type('file')->name('mailer.yml')->in($this->getProjectConfigDirectories());
    $found = array();
    foreach ($files as $file)
    {
      $found[$file] = true;
    }

    // modules/*/validate/*.yml
    $files = sfFinder::type('file')->name('*.yml')->in(glob(sfConfig::get('sf_apps_dir').'/*/modules/*/validate'));
    foreach ($files as $file)
    {
      $found[$file] = true;
    }

    return $found;
  }
}
