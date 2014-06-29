<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Try autoloading using composer if available.
if (!file_exists($autoload = __DIR__.'/../../../../autoload.php'))
{
  $autoload = __DIR__.'/../../autoload.php';
}

// Fall back to classic Symfony loading
if (!file_exists($autoload))
{
  require_once(__DIR__.'/../autoload/sfCoreAutoload.class.php');
  sfCoreAutoload::register();
}
else
{
  require_once $autoload;
}

try
{
  $dispatcher = new sfEventDispatcher();
  $logger = new sfCommandLogger($dispatcher);

  $application = new sfSymfonyCommandApplication($dispatcher, null, array('symfony_lib_dir' => realpath(__DIR__.'/..')));
  $statusCode = $application->run();
}
catch (Exception $e)
{
  if (!isset($application))
  {
    throw $e;
  }

  $application->renderException($e);
  $statusCode = $e->getCode();

  exit(is_numeric($statusCode) && $statusCode ? $statusCode : 1);
}

exit(is_numeric($statusCode) ? $statusCode : 0);
