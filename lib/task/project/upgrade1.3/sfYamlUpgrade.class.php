<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Lists YAML files that use the deprecated Boolean notations.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfYamlUpgrade extends sfUpgrade
{
  public function upgrade()
  {
    $specVersion = sfYaml::getSpecVersion();

    $queue = array();
    $success = true;

    $finder = sfFinder::type('file')->name('*.yml')->prune('vendor');
    foreach ($finder->in(sfConfig::get('sf_root_dir')) as $file)
    {
      // attempt to upgrade booleans
      $original = file_get_contents($file);
      $upgraded = sfToolkit::pregtr($original, array(
        '/^([^:]+: +)(?:on|y(?:es)?|\+)(\s*)$/im' => '\\1true\\2',
        '/^([^:]+: +)(?:off|no?|-)(\s*)$/im'      => '\\1false\\2',
      ));

      try
      {
        sfYaml::setSpecVersion('1.1');
        $yaml11 = sfYaml::load($original);

        sfYaml::setSpecVersion('1.2');
        $yaml12 = sfYaml::load($upgraded);
      }
      catch (Exception $e)
      {
        // unable to load the YAML
        $yaml11 = 'foo';
        $yaml12 = 'bar';
      }

      if ($yaml11 == $yaml12)
      {
        if ($original != $upgraded)
        {
          $this->getFilesystem()->touch($file);
          file_put_contents($file, $upgraded);
        }
      }
      else
      {
        $this->logSection('yaml', 'Unable to upgrade '.sfDebug::shortenFilePath($file), null, 'ERROR');

        // force project to use YAML 1.1 spec
        if ('1.1' != $specVersion)
        {
          $specVersion = '1.1';

          $class = sfClassManipulator::fromFile(sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php');

          $original = $class->getCode();
          $modified = $class->wrapMethod('setup', 'sfYaml::setSpecVersion(\'1.1\');');

          if ($original != $modified && $this->askConfirmation(array(
            'Unable to convert YAML file:',
            sfDebug::shortenFilePath($file),
            '',
            'Would you like to force YAML to be parsed with the 1.1 specification? (Y/n)',
          ), 'QUESTION_LARGE'))
          {
            $this->logSection('yaml', 'Forcing YAML 1.1 spec');

            $this->getFilesystem()->touch($class->getFile());
            $class->save();
          }
          else
          {
            $this->logBlock(array('Unable to either upgrade YAML files or force 1.1 spec.', '(see UPGRADE_TO_1_3 file for more information)'), 'ERROR_LARGE');
          }
        }

        $success = false;
      }
    }

    if ($success && '1.1' == $specVersion)
    {
      $file = sfConfig::get('sf_config_dir').'/ProjectConfiguration.class.php';
      $original = file_get_contents($file);
      $modified = preg_replace('/^\s*sfYaml::setSpecVersion\(\'1\.1\'\);\n/im', '', $original);

      if ($original != $modified)
      {
        $this->logSection('yaml', 'Removing setting of YAML 1.1 spec');

        $this->getFilesystem()->touch($file);
        file_put_contents($file, $modified);
      }
    }
  }
}
