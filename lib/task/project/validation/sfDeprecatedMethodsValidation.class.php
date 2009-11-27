<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Finds deprecated methods usage.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfAssetsUpgrade.class.php 24395 2009-11-25 19:02:18Z Kris.Wallsmith $
 */
class sfDeprecatedMethodsValidation extends sfValidation
{
  public function getHeader()
  {
    return 'Checking usage of deprecated methods';
  }

  public function getExplanation()
  {
    return array(
          '',
          '  The files above use deprecated functions and/or methods',
          '  that have been removed in symfony 1.4.',
          '',
          '  You can find a list of all deprecated methods under the',
          '  "Methods and Functions" section of the DEPRECATED tutorial:',
          '',
          '  http://www.symfony-project.org/tutorial/1_4/en/deprecated',
          '',
    );
  }

  public function validate()
  {
    $found = array_merge(
      $this->doValidate(array(
        'sfToolkit\:\:getTmpDir',
        'sfToolkit\:\:removeArrayValueForPath',
        'sfToolkit\:\:hasArrayValueForPath',
        'sfToolkit\:\:getArrayValueForPathByRef',
        'sfValidatorBase\:\:setInvalidMessage',
        'sfValidatorBase\:\:setRequiredMessage',
        'debug_message',
        'sfContext\:\:retrieveObjects',
        '\-\>getXDebugStack',
        '\-\>checkSymfonyVersion',
      ), sfConfig::get('sf_root_dir')),

      $this->doValidate(array(
        'contains', 'responseContains', 'isRequestParameter', 'isResponseHeader',
        'isUserCulture', 'isRequestFormat', 'checkResponseElement', '\-\>sh\(',
      ), sfConfig::get('sf_test_dir')),

      $this->doValidate(array(
        'getDefaultView', 'handleError', 'validate', 'debugMessage', 'getController\(\)\-\>sendEmail'
      ), $this->getProjectActionDirectories())
    );

    return $found;
  }

  public function doValidate($methods, $dir)
  {
    $found = array();
    $files = sfFinder::type('file')->name('*.php')->prune('vendor')->in($dir);
    foreach ($files as $file)
    {
      $content = file_get_contents($file);

      $matches = array();
      foreach ($methods as $method)
      {
        if (false !== stripos($content, $method))
        {
          $matches[] = $method;
        }
      }

      if ($matches)
      {
        $found[$file] = implode(', ', $matches);
      }
    }

    return $found;
  }
}
