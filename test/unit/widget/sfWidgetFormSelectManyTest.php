<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(1);

$dom = new DOMDocument('1.0', 'utf-8');
$dom->validateOnParse = true;

// ->render()
$t->diag('->render()');
$w = new sfWidgetFormSelectMany(['choices' => ['foo' => 'bar', 'foobar' => 'foo']]);
$t->is($w->getOption('multiple'), true, '__construct() creates a multiple select tag');
