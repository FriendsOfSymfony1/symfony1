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

$t = new \lime_test(1);

$w = new \sfWidgetFormInputFileMulti();

// ->render()
$t->diag('->render() multiple file upload');
$t->is($w->render('foo'), '<input type="file" name="foo[]" multiple="1" id="foo" />', '->render() renders the widget as HTML');
