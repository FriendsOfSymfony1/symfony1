<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Upgrades Propel.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfPropelUpgrade extends sfUpgrade
{
  static protected
    $inserts = array(
      'propel.behavior.default'                        => 'symfony,symfony_i18n',
      'propel.behavior.symfony.class'                  => 'plugins.sfPropelPlugin.lib.behavior.SfPropelBehaviorSymfony',
      'propel.behavior.symfony_i18n.class'             => 'plugins.sfPropelPlugin.lib.behavior.SfPropelBehaviorI18n',
      'propel.behavior.symfony_i18n_translation.class' => 'plugins.sfPropelPlugin.lib.behavior.SfPropelBehaviorI18nTranslation',
      'propel.behavior.symfony_behaviors.class'        => 'plugins.sfPropelPlugin.lib.behavior.SfPropelBehaviorSymfonyBehaviors',
      'propel.behavior.symfony_timestampable.class'    => 'plugins.sfPropelPlugin.lib.behavior.SfPropelBehaviorTimestampable',
    ),
    $removes = array(
      'propel.builder.peer.class'              => 'plugins.sfPropelPlugin.lib.builder.SfPeerBuilder',
      'propel.builder.object.class'            => 'plugins.sfPropelPlugin.lib.builder.SfObjectBuilder',
      'propel.builder.objectstub.class'        => 'plugins.sfPropelPlugin.lib.builder.SfExtensionObjectBuilder',
      'propel.builder.peerstub.class'          => 'plugins.sfPropelPlugin.lib.builder.SfExtensionPeerBuilder',
      'propel.builder.objectmultiextend.class' => 'plugins.sfPropelPlugin.lib.builder.SfMultiExtendObjectBuilder',
      'propel.builder.mapbuilder.class'        => 'plugins.sfPropelPlugin.lib.builder.SfMapBuilderBuilder',
      'propel.builder.nestedset.class'         => 'plugins.sfPropelPlugin.lib.builder.SfNestedSetBuilder',
      'propel.builder.nestedsetpeer.class'     => 'plugins.sfPropelPlugin.lib.builder.SfNestedSetPeerBuilder',
    );

  protected
    $properties = null;

  public function upgrade()
  {
    if (!in_array('sfPropelPlugin', $this->configuration->getPlugins()))
    {
      if (file_exists($file = sfConfig::get('sf_config_dir').'/propel.ini'))
      {
        $this->getFilesystem()->remove($file);
      }

      return;
    }

    if (
      file_exists($old = sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php')
      &&
      !file_exists($new = sfConfig::get('sf_lib_dir').'/filter/BaseFormFilterPropel.class.php')
    )
    {
      $this->getFilesystem()->rename($old, $new);
    }

    if (file_exists($file = sfConfig::get('sf_config_dir').'/propel.ini'))
    {
      // use phing to parse propel.ini
      sfPhing::startup();
      $this->properties = new Properties();
      $this->properties->load(new PhingFile($file));

      $modified = $original = file_get_contents($file);
      $modified = $this->upgradePropelIni($modified, self::$removes, false);
      $modified = $this->upgradePropelIni($modified, self::$inserts, true);

      if ($original != $modified)
      {
        $this->logSection('propel', 'Upgrading '.sfDebug::shortenFilePath($file));
        file_put_contents($file, $modified);
      }
    }
  }

  protected function upgradePropelIni($contents, $directives, $insert = false)
  {
    static $header = false;

    $failures = array();

    foreach ($directives as $key => $value)
    {
      $current = $this->properties->get($key);

      if (null === $current)
      {
        if ($insert)
        {
          if (!$header)
          {
            $contents = rtrim($contents).PHP_EOL.PHP_EOL.'; symfony 1.3 upgrade ('.date('Y/m/d H:i:s').')'.PHP_EOL;
            $header = true;
          }

          // insert now
          $contents = $contents.$key.' = '.$value.PHP_EOL;
          $this->properties->setProperty($key, $value);
        }
      }
      else if ($value == $current)
      {
        if (!$insert)
        {
          // remove now
          $contents = preg_replace('/^'.preg_quote($key).'[\s=]/m', ';\\0', $contents);
          $this->properties->setProperty($key, null);
        }
      }
      else
      {
        $failures[] = $key;
      }
    }

    if ($failures)
    {
      $this->logBlock(array_merge(
        array(sprintf('Please %s or upgrade the following propel.ini directive(s):', $insert ? 'insert' : 'remove'), ''),
        array_map(create_function('$v', 'return \' - \'.$v;'), $failures)
      ), 'ERROR_LARGE');
    }

    return $contents;
  }
}
