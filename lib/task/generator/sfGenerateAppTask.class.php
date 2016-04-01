<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(__DIR__.'/sfGeneratorBaseTask.class.php');

/**
 * Generates a new application.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfGenerateAppTask extends sfGeneratorBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('app', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('escaping-strategy', null, sfCommandOption::PARAMETER_REQUIRED, 'Output escaping strategy', true),
      new sfCommandOption('csrf-secret', null, sfCommandOption::PARAMETER_REQUIRED, 'Secret to use for CSRF protection', true),
    ));

    $this->namespace = 'generate';
    $this->name = 'app';

    $this->briefDescription = 'Generates a new application';

    $this->detailedDescription = <<<EOF
The [generate:app|INFO] task creates the basic directory structure
for a new application in the current project:

  [./symfony generate:app frontend|INFO]

This task also creates two front controller scripts in the
[web/|COMMENT] directory:

  [web/%application%.php|INFO]     for the production environment
  [web/%application%_dev.php|INFO] for the development environment

For the first application, the production environment script is named
[index.php|COMMENT].

If an application with the same name already exists,
it throws a [sfCommandException|COMMENT].

By default, the output escaping is enabled (to prevent XSS), and a random
secret is also generated to prevent CSRF.

You can disable output escaping by using the [escaping-strategy|COMMENT]
option:

  [./symfony generate:app frontend --escaping-strategy=false|INFO]

You can enable session token in forms (to prevent CSRF) by defining
a secret with the [csrf-secret|COMMENT] option:

  [./symfony generate:app frontend --csrf-secret=UniqueSecret|INFO]

You can customize the default skeleton used by the task by creating a
[%sf_data_dir%/skeleton/app|COMMENT] directory.
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $app = $arguments['app'];

    // Validate the application name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $app))
    {
      throw new sfCommandException(sprintf('The application name "%s" is invalid.', $app));
    }

    $appDir = sfConfig::get('sf_apps_dir').'/'.$app;

    if (is_dir($appDir))
    {
      throw new sfCommandException(sprintf('The application "%s" already exists.', $appDir));
    }

    if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/app'))
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/app';
    }
    else
    {
      $skeletonDir = __DIR__.'/skeleton/app';
    }

    // Create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror($skeletonDir.'/app', $appDir, $finder);

    // Create $app.php or index.php if it is our first app
    $indexName = 'index';
    $firstApp = !file_exists(sfConfig::get('sf_web_dir').'/index.php');
    if (!$firstApp)
    {
      $indexName = $app;
    }

    if (true === $options['csrf-secret'])
    {
      $options['csrf-secret'] = sha1(mt_rand(11111111, 99999999).getmypid());
    }

    // Set no_script_name value in settings.yml for production environment
    $finder = sfFinder::type('file')->name('settings.yml');
    $this->getFilesystem()->replaceTokens($finder->in($appDir.'/config'), '##', '##', array(
      'NO_SCRIPT_NAME'    => $firstApp ? 'true' : 'false',
      'CSRF_SECRET'       => sfYamlInline::dump(sfYamlInline::parseScalar($options['csrf-secret'])),
      'ESCAPING_STRATEGY' => sfYamlInline::dump((boolean) sfYamlInline::parseScalar($options['escaping-strategy'])),
      'USE_DATABASE'      => sfConfig::has('sf_orm') ? 'true' : 'false',
    ));

    $this->getFilesystem()->copy($skeletonDir.'/web/index.php', sfConfig::get('sf_web_dir').'/'.$indexName.'.php');
    $this->getFilesystem()->copy($skeletonDir.'/web/index.php', sfConfig::get('sf_web_dir').'/'.$app.'_dev.php');

    $this->getFilesystem()->replaceTokens(sfConfig::get('sf_web_dir').'/'.$indexName.'.php', '##', '##', array(
      'APP_NAME'    => $app,
      'ENVIRONMENT' => 'prod',
      'IS_DEBUG'    => 'false',
      'IP_CHECK'    => '',
    ));

    $this->getFilesystem()->replaceTokens(sfConfig::get('sf_web_dir').'/'.$app.'_dev.php', '##', '##', array(
      'APP_NAME'    => $app,
      'ENVIRONMENT' => 'dev',
      'IS_DEBUG'    => 'true',
      'IP_CHECK'    => '// this check prevents access to debug front controllers that are deployed by accident to production servers.'.PHP_EOL.
                       '// feel free to remove this, extend it or make something more sophisticated.'.PHP_EOL.
                       'if (!in_array(@$_SERVER[\'REMOTE_ADDR\'], array(\'127.0.0.1\', \'::1\')))'.PHP_EOL.
                       '{'.PHP_EOL.
                       '  die(\'You are not allowed to access this file. Check \'.basename(__FILE__).\' for more information.\');'.PHP_EOL.
                       '}'.PHP_EOL,
    ));

    $this->getFilesystem()->rename($appDir.'/config/ApplicationConfiguration.class.php', $appDir.'/config/'.$app.'Configuration.class.php');

    $this->getFilesystem()->replaceTokens($appDir.'/config/'.$app.'Configuration.class.php', '##', '##', array('APP_NAME' => $app));

    $fixPerms = new sfProjectPermissionsTask($this->dispatcher, $this->formatter);
    $fixPerms->setCommandApplication($this->commandApplication);
    $fixPerms->setConfiguration($this->configuration);
    $fixPerms->run();

    // Create test dir
    $this->getFilesystem()->mkdirs(sfConfig::get('sf_test_dir').'/functional/'.$app);
  }
}
