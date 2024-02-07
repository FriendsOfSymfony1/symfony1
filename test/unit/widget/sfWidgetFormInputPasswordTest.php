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

$t = new \lime_test(3);

$w = new \sfWidgetFormInputPassword();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo'), '<input type="password" name="foo" id="foo" />', '->render() renders the widget as HTML');

$t->is($w->render('foo', 'bar'), '<input type="password" name="foo" id="foo" />', '->render() renders the widget as HTML');

$w->setOption('always_render_empty', false);
$t->is($w->render('foo', 'bar'), '<input type="password" name="foo" value="bar" id="foo" />', '->render() renders the widget as HTML');
