<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Migrates form classes.
 *
 * @package    symfony
 * @subpackage task
 * @author     Pascal Borreli <pborreli@sqli.com>
 * @version    SVN: $Id$
 */
class sfFormsUpgrade extends sfUpgrade
{
  public function upgrade()
  {
    if (!file_exists($file = sfConfig::get('sf_lib_dir').'/form/BaseForm.class.php'))
    {
      $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);
      $tokens = array(
        'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
        'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here'
      );

      $this->getFilesystem()->copy(sfConfig::get('sf_symfony_lib_dir').'/task/generator/skeleton/project/lib/form/BaseForm.class.php', $file);
      $this->getFilesystem()->replaceTokens(array($file), '##', '##', $tokens);
    }

    $finder = sfFinder::type('file')->name('*.php');
    foreach ($finder->in($this->getProjectLibDirectories('/form')) as $file)
    {
      $contents = file_get_contents($file);
      $changed = false;

      // forms that extend sfForm should now extend BaseForm
      $contents = preg_replace('/(\bextends\s+)sfForm\b/i', '\\1BaseForm', $contents, -1, $count);
      $changed = $count || $changed;

      // change instances of sfWidgetFormInput to sfWidgetFormInputText
      $contents = preg_replace('/\bnew\s+sfWidgetFormInput\b/i', '\\0Text', $contents, -1, $count);
      $changed = $count || $changed;

      // change signature of sfFormDoctrine::processValues()
      $contents = preg_replace('/public\s+function\s+processValues\s*\(\s*\$\w+(\s*=\s*null\s*)\)/ie', "str_replace('$1', '', '$0')", $contents, -1, $count);
      $changed = $count || $changed;

      if ($changed)
      {
        $this->logSection('form', 'Migrating '.$file);
        file_put_contents($file, $contents);
      }
    }
  }
}
