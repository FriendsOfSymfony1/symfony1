<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(9);

// __construct()
$t->diag('__construct()');

try {
    new sfValidatorEqual();
    $t->fail('->__construct() throws an "RuntimeException" if you don\'t pass a "value" option');
} catch (RuntimeException $e) {
    $t->pass('->__construct() throws an "RuntimeException" if you don\'t pass a "value" option');
}

$v = new sfValidatorEqual(['value' => 'foo']);

// ->clean()
$t->diag('->clean()');
$t->is($v->clean('foo'), 'foo', '->clean() returns the value unmodified');

$v->setOption('value', '0');
$t->ok(0 === $v->clean(0), '->clean() returns the value unmodified');

try {
    $v->clean('bar');
    $t->fail('->clean() fails values are not equal');
    $t->skip('', 1);
} catch (sfValidatorError $e) {
    $t->pass('->clean() fails values are not equal');
    $t->is($e->getCode(), 'not_equal', '->clean() throws a sfValidatorError');
}

$v->setMessage('not_equal', 'Not equal');

try {
    $v->clean('bar');
    $t->fail('"not_equal" error message customization');
} catch (sfValidatorError $e) {
    $t->is($e->getMessage(), 'Not equal', '"not_equal" error message customization');
}

$v->setOption('strict', true);
$v->setOption('value', '0');

try {
    $v->clean(0);
    $t->fail('"strict" option set the operator for comparaison');
    $t->skip('', 1);
} catch (sfValidatorError $e) {
    $t->pass('"strict" option set the operator for comparaison');
    $t->is($e->getCode(), 'not_strictly_equal', '->clean() throws a sfValidatorError');
}

$v->setMessage('not_strictly_equal', 'Not strictly equal');

try {
    $v->clean(0);
    $t->fail('"not_strictly_equal" error message customization');
} catch (sfValidatorError $e) {
    $t->is($e->getMessage(), 'Not strictly equal', '"not_strictly_equal" error message customization');
}
