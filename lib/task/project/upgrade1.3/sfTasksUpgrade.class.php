<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Renames references to renamed task classes.
 *
 * @package    symfony
 * @subpackage task
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfTasksUpgrade extends sfUpgrade
{
  public function upgrade()
  {
    $finder = sfFinder::type('file')->name('*.php');
    foreach ($finder->in($this->getProjectLibDirectories('/task')) as $file)
    {
      $contents = file_get_contents($file);
      $changed = false;

      // renamed data task classes
      if (preg_match_all('/\bnew\b\s+\bsf(Doctrine|Propel)(Load|Dump)DataTask\b/i', $contents, $matches, PREG_OFFSET_CAPTURE))
      {
        foreach ($matches[0] as $match)
        {
          list($search, $offset) = $match;
          $replace = str_ireplace(array('LoadData', 'DumpData'), array('DataLoad', 'DataDump'), $search);

          $contents = substr($contents, 0, $offset).$replace.substr($contents, $offset + strlen($search));
        }

        $changed = true;
      }

      // sfConfigureDatabaseTask was renamed to sf(Doctrine|Propel)ConfigureDatabaseTask
      if (preg_match_all('/\bnew\b\s+\bsfConfigureDatabaseTask\b/i', $contents, $matches, PREG_OFFSET_CAPTURE))
      {
        $newTask = sprintf('sf%sConfigureDatabaseTask', ucfirst(sfConfig::get('sf_orm')));
        foreach ($matches[0] as $match)
        {
          list($search, $offset) = $match;
          $replace = str_ireplace('sfConfigureDatabaseTask', $newTask, $search);

          $contents = substr($contents, 0, $offset).$replace.substr($contents, $offset + strlen($search));
        }

        $changed = true;
      }

      if ($changed)
      {
        $this->logSection('task', 'Migrating '.$file);
        file_put_contents($file, $contents);
      }
    }
  }
}
