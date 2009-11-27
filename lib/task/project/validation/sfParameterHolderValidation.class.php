<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Finds usage of array notation with a parameter holder.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfAssetsUpgrade.class.php 24395 2009-11-25 19:02:18Z Kris.Wallsmith $
 */
class sfParameterHolderValidation extends sfValidation
{
  public function getHeader()
  {
    return 'Checking usage of array notation with a parameter holder';
  }

  public function getExplanation()
  {
    return array(
          '',
          '  The files above use the array notation with a parameter holder,',
          '  which is not supported anymore in symfony 1.4.',
          '  For instance, you need to change this construct:',
          '',
          '    $foo = $request->getParameter(\'foo[bar]\')',
          '',
          '  to this one:',
          '',
          '    $params = $request->getParameter(\'foo\')',
          '    $foo = $params[\'bar\'])',
          '',
    );
  }

  public function validate()
  {
    $found = array();
    $files = sfFinder::type('file')->name('*.class.php')->prune('vendor')->in(sfConfig::get('sf_root_dir'));
    foreach ($files as $file)
    {
      $content = file_get_contents($file);

      if (preg_match('#(get|has|remove)(Request)*Parameter\([^\)]*?\[[^\),]#', $content))
      {
        $found[$file] = true;
      }
    }

    return $found;
  }
}
