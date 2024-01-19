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

$t = new \lime_test(4);

$w = new \sfWidgetFormTextarea();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 'bar'), '<textarea rows="4" cols="30" name="foo" id="foo">bar</textarea>', '->render() renders the widget as HTML');
$t->is($w->render('foo', '<bar>'), '<textarea rows="4" cols="30" name="foo" id="foo">&lt;bar&gt;</textarea>', '->render() escapes the content');
$t->is($w->render('foo', '&lt;bar&gt;'), '<textarea rows="4" cols="30" name="foo" id="foo">&lt;bar&gt;</textarea>', '->render() does not double escape content');

// change default attributes
$t->diag('change default attributes');
$w->setAttribute('rows', 10);
$t->is($w->render('foo', 'bar'), '<textarea rows="10" cols="30" name="foo" id="foo">bar</textarea>', '->render() renders the widget as HTML');
