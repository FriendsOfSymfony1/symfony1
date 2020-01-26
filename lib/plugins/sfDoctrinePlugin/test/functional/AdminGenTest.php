<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'backend';
$fixtures = 'fixtures';
require_once(__DIR__.'/../bootstrap/functional.php');
require_once(__DIR__.'/AdminGenBrowser.class.php');

$b = new AdminGenBrowser();
$b->runTests();