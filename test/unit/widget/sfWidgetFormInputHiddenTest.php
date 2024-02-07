<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new \lime_test(2);

$w = new \sfWidgetFormInputHidden();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo'), '<input type="hidden" name="foo" id="foo" />', '->render() renders the widget as HTML');

// ->isHidden()
$t->diag('->isHidden()');
$t->is($w->isHidden(), true, '->isHidden() returns true');
