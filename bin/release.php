<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

if (!isset($argv[1])) {
    throw new \InvalidArgumentException('You must provide version.');
}

$filesystem = new sfFilesystem();

$version = $argv[1];

echo sprintf("Releasing symfony version \"%s\".\n", $version);

exit(0);
