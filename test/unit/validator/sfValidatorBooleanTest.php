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

$t = new \lime_test(23);

$v = new \sfValidatorBoolean();

// ->clean()
$t->diag('->clean()');

// true values
$t->diag('true values');
foreach ($v->getOption('true_values') as $true_value) {
    $t->is($v->clean($true_value), true, '->clean() returns true if the value is in the true_values option');
}

// false values
$t->diag('false values');
foreach ($v->getOption('false_values') as $false_value) {
    $t->is($v->clean($false_value), false, '->clean() returns false if the value is in the false_values option');
}

// other special test cases
$t->is($v->clean(0), false, '->clean() returns false if the value is 0');
$t->is($v->clean(false), false, '->clean() returns false if the value is false');
$t->is($v->clean(1), true, '->clean() returns true if the value is 1');
$t->is($v->clean(true), true, '->clean() returns true if the value is true');
$t->is($v->clean(''), false, '->clean() returns false if the value is empty string as empty_value is false by default');

class MyFalseClass
{
    public function __toString()
    {
        return 'false';
    }
}
$t->is($v->clean(new \MyFalseClass()), false, '->clean() returns false if the value is false');

// required is false by default
$t->is($v->clean(null), false, '->clean() returns false if the value is null');

try {
    $v->clean('astring');
    $t->fail('->clean() throws an error if the input value is not a true or a false value');
    $t->skip('', 1);
} catch (\sfValidatorError $e) {
    $t->pass('->clean() throws an error if the input value is not a true or a false value');
    $t->is($e->getCode(), 'invalid', '->clean() throws a sfValidatorError');
}

// empty
$t->diag('empty');
$v->setOption('required', false);
$t->ok(false === $v->clean(null), '->clean() returns false if no value is given');
$v->setOption('empty_value', true);
$t->ok(true === $v->clean(null), '->clean() returns the value of the empty_value option if no value is given');
