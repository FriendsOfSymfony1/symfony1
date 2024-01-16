<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(6);

$w = new sfWidgetFormInputRead();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo'), '<input type="hidden" name="foo" id="foo" /><input type="text" readonly="readonly" style="border: 0;" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', 'bar'), '<input type="hidden" name="foo" value="bar" id="foo" /><input type="text" value="bar" readonly="readonly" style="border: 0;" />', '->render() can take a value for the input');
$t->is($w->render('foo', '', ['class' => 'foobar', 'style' => 'width: 500px;']), '<input type="hidden" name="foo" value="" id="foo" /><input type="text" value="" readonly="readonly" style="border: 0; width: 500px;" class="foobar" />', '->render() can take HTML attributes as its third argument');

$w = new sfWidgetFormInputRead(['text' => 'Read text']);
$t->is($w->render('foo', 'bar'), '<input type="hidden" name="foo" value="bar" id="foo" /><input type="text" value="Read text" readonly="readonly" style="border: 0;" />', '->render() can take a value for the input and another value for read input');

$w = new sfWidgetFormInputRead([], ['class' => 'foobar', 'style' => 'width: 500px;']);
$t->is($w->render('foo'), '<input type="hidden" name="foo" id="foo" /><input type="text" readonly="readonly" style="border: 0; width: 500px;" class="foobar" />', '__construct() can take default HTML attributes');
$t->is($w->render('foo', null, ['class' => 'barfoo']), '<input type="hidden" name="foo" id="foo" /><input type="text" readonly="readonly" style="border: 0;" class="barfoo" />', '->render() can override default attributes');
