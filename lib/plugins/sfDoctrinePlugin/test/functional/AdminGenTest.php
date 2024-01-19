<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'backend';
$fixtures = 'fixtures';

require_once dirname(__FILE__).'/../bootstrap/functional.php';

require_once dirname(__FILE__).'/AdminGenBrowser.class.php';

$b = new \AdminGenBrowser();
$b->runTests();
