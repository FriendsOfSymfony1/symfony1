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

$t = new \lime_test(114);

$v = new \sfValidatorSchemaCompare('left', \sfValidatorSchemaCompare::EQUAL, 'right');

// ->clean()
$t->diag('->clean()');
foreach ([
    [['left' => 'foo', 'right' => 'foo'], \sfValidatorSchemaCompare::EQUAL],
    [[], \sfValidatorSchemaCompare::EQUAL],
    [null, \sfValidatorSchemaCompare::EQUAL],
    [['left' => 1, 'right' => 2], \sfValidatorSchemaCompare::LESS_THAN],
    [['left' => 2, 'right' => 2], \sfValidatorSchemaCompare::LESS_THAN_EQUAL],
    [['left' => 2, 'right' => 1], \sfValidatorSchemaCompare::GREATER_THAN],
    [['left' => 2, 'right' => 2], \sfValidatorSchemaCompare::GREATER_THAN_EQUAL],
    [['left' => 'foo', 'right' => 'bar'], \sfValidatorSchemaCompare::NOT_EQUAL],
    [['left' => '0000', 'right' => '0'], \sfValidatorSchemaCompare::NOT_IDENTICAL],
    [['left' => '0000', 'right' => '0'], \sfValidatorSchemaCompare::EQUAL],
    [['left' => '0000', 'right' => '0000'], \sfValidatorSchemaCompare::IDENTICAL],

    [['left' => 'foo', 'right' => 'foo'], '=='],
    [[], '=='],
    [null, '=='],
    [['left' => 1, 'right' => 2], '<'],
    [['left' => 2, 'right' => 2], '<='],
    [['left' => 2, 'right' => 1], '>'],
    [['left' => 2, 'right' => 2], '>='],
    [['left' => 'foo', 'right' => 'bar'], '!='],
    [['left' => '0000', 'right' => '0'], '!=='],
    [['left' => '0000', 'right' => '0'], '=='],
    [['left' => '0000', 'right' => '0000'], '==='],
] as $values) {
    $v->setOption('operator', $values[1]);
    $t->is($v->clean($values[0]), $values[0], '->clean() checks that the values match the comparison');
}

foreach ([
    [['left' => 'foo', 'right' => 'foo'], \sfValidatorSchemaCompare::NOT_EQUAL],
    [[], \sfValidatorSchemaCompare::NOT_EQUAL],
    [null, \sfValidatorSchemaCompare::NOT_EQUAL],
    [['left' => 1, 'right' => 2], \sfValidatorSchemaCompare::GREATER_THAN],
    [['left' => 2, 'right' => 3], \sfValidatorSchemaCompare::GREATER_THAN_EQUAL],
    [['left' => 2, 'right' => 1], \sfValidatorSchemaCompare::LESS_THAN],
    [['left' => 3, 'right' => 2], \sfValidatorSchemaCompare::LESS_THAN_EQUAL],
    [['left' => 'foo', 'right' => 'bar'], \sfValidatorSchemaCompare::EQUAL],
    [['left' => '0000', 'right' => '0'], \sfValidatorSchemaCompare::IDENTICAL],
    [['left' => '0000', 'right' => '0'], \sfValidatorSchemaCompare::NOT_EQUAL],
    [['left' => '0000', 'right' => '0000'], \sfValidatorSchemaCompare::NOT_IDENTICAL],

    [['left' => 'foo', 'right' => 'foo'], '!='],
    [[], '!='],
    [null, '!='],
    [['left' => 1, 'right' => 2], '>'],
    [['left' => 2, 'right' => 3], '>='],
    [['left' => 2, 'right' => 1], '<'],
    [['left' => 3, 'right' => 2], '<='],
    [['left' => 'foo', 'right' => 'bar'], '=='],
    [['left' => '0000', 'right' => '0'], '==='],
    [['left' => '0000', 'right' => '0'], '!='],
    [['left' => '0000', 'right' => '0000'], '!=='],
] as $values) {
    $v->setOption('operator', $values[1]);

    foreach ([true, false] as $globalError) {
        $v->setOption('throw_global_error', $globalError);

        try {
            $v->clean($values[0]);
            $t->fail('->clean() throws an sfValidatorError if the value is the comparison failed');
            $t->skip('', 1);
        } catch (\sfValidatorError $e) {
            $t->pass('->clean() throws an sfValidatorError if the value is the comparison failed');
            $t->is($e->getCode(), $globalError ? 'invalid' : 'left [invalid]', '->clean() throws a sfValidatorError');
        }
    }
}

try {
    $v->clean('foo');
    $t->fail('->clean() throws an InvalidArgumentException exception if the first argument is not an array of value');
} catch (\InvalidArgumentException $e) {
    $t->pass('->clean() throws an InvalidArgumentException exception if the first argument is not an array of value');
}

$v = new \sfValidatorSchemaCompare('left', 'foo', 'right');

try {
    $v->clean([]);
    $t->fail('->clean() throws an InvalidArgumentException exception if the operator does not exist');
} catch (\InvalidArgumentException $e) {
    $t->pass('->clean() throws an InvalidArgumentException exception if the operator does not exist');
}

// ->asString()
$t->diag('->asString()');
$v = new \sfValidatorSchemaCompare('left', \sfValidatorSchemaCompare::EQUAL, 'right');
$t->is($v->asString(), 'left == right', '->asString() returns a string representation of the validator');

$v = new \sfValidatorSchemaCompare('left', \sfValidatorSchemaCompare::EQUAL, 'right', [], ['required' => 'This is required.']);
$t->is($v->asString(), 'left ==({}, { required: \'This is required.\' }) right', '->asString() returns a string representation of the validator');
