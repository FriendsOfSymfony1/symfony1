<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Launches a plugin test suite.
 *
 * @package     symfony
 * @subpackage  task
 * @author      Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version     SVN: $Id$
 */
class sfTestPluginTask extends sfTestBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('only', null, sfCommandOption::PARAMETER_REQUIRED, 'Only run "unit" or "functional" tests'),
    ));

    $this->namespace = 'test';
    $this->name = 'plugin';

    $this->briefDescription = 'Launches a plugin test suite';

    $this->detailedDescription = <<<EOF
The [test:plugin|INFO] task launches a plugin's test suite:

  [./symfony test:plugin sfExamplePlugin|INFO]

You can specify only unit or functional tests with the [--only|COMMENT] option:

  [./symfony test:plugin sfExamplePlugin --only=unit|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (false === $this->checkPluginExists($arguments['plugin']))
    {
      throw new sfCommandException(sprintf('The plugin "%s" does not exists', $arguments['plugin']));
    }

    if ($options['only'] && !in_array($options['only'], array('unit', 'functional')))
    {
      throw new sfCommandException(sprintf('The --only option must be either "unit" or "functional" ("%s" given)', $options['only']));
    }

    require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php';

    $h = new lime_harness(new lime_output_color());
    $h->base_dir = sfConfig::get('sf_plugins_dir').'/'.$arguments['plugin'].'/test/'.$options['only'];

    $finder = sfFinder::type('file')->follow_link()->name('*Test.php');
    $h->register($finder->in($h->base_dir));

    $h->run();
  }
}
