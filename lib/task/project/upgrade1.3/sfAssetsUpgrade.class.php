<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Tries to fix the removal of the common filter.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfAssetsUpgrade extends sfUpgrade
{
  public function upgrade()
  {
    // remove the common filter from all filters.yml configuration file
    $dirs = $this->getProjectConfigDirectories();
    foreach ($dirs as $dir)
    {
      if (!file_exists($file = $dir.'/filters.yml'))
      {
        continue;
      }

      $content = preg_replace("#^common\:\s+~#m", '', file_get_contents($file), -1, $count);
      if (!$count)
      {
        continue;
      }

      $this->logSection('filters', sprintf('Migrating %s', $file));
      file_put_contents($file, $content);
    }

    // add calls to replacing helpers in all layouts
    $finder = $this->getFinder('file')->name('*.php');
    $dirs = glob(sfConfig::get('sf_apps_dir').'/*/templates');
    foreach ($finder->in($dirs) as $file)
    {
      $content = file_get_contents($file);

      if (preg_match('/include_(stylesheets|javascripts)\(\)/', $content))
      {
        continue;
      }

      if (false === ($pos = strpos($content, '</head>')))
      {
        continue;
      }

      $code = <<<EOF

    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>

EOF;

      $content = substr($content, 0, $pos).$code.substr($content, $pos);

      $this->logSection('layout', sprintf('Migrating %s', $file));
      file_put_contents($file, $content);
    }
  }
}
