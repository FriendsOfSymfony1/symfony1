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

try {
    new sfValidatorDateRange();
    $t->fail('__construct() throws a sfValidatorError if you don\'t pass a from_date and a to_date option');
    $t->skip('', 1);
} catch (RuntimeException $e) {
    $t->pass('__construct() throws a RuntimeException if you don\'t pass a from_date and a to_date option');
}

$v = new sfValidatorDateRange([
    'from_date' => new sfValidatorDate(['required' => false]),
    'to_date' => new sfValidatorDate(['required' => false]),
]);

// ->clean()
$t->diag('->clean()');

$values = $v->clean(['from' => '2008-01-01', 'to' => '2009-01-01']);
$t->is($values, ['from' => '2008-01-01', 'to' => '2009-01-01'], '->clean() returns the from and to values');

try {
    $v->clean(['from' => '2008-01-01', 'to' => '1998-01-01']);
    $t->fail('->clean() throws a sfValidatorError if the from date is after the to date');
    $t->skip('', 1);
} catch (sfValidatorError $e) {
    $t->pass('->clean() throws a sfValidatorError if the from date is after the to date');
    $t->is($e->getCode(), 'invalid', '->clean() throws a sfValidatorError');
}

// custom field names
$t->diag('Custom field names options');

$v = new sfValidatorDateRange([
    'from_date' => new sfValidatorDate(['required' => true]),
    'to_date' => new sfValidatorDate(['required' => true]),
    'from_field' => 'custom_from',
    'to_field' => 'custom_to',
]);

try {
    $v->clean(['from' => '2008-01-01', 'to' => '1998-01-01']);
    $t->fail('->clean() take into account custom fields');
} catch (sfValidatorError $e) {
    $t->pass('->clean() take into account custom fields');
}

$values = $v->clean(['custom_from' => '2008-01-01', 'custom_to' => '2009-01-01']);
$t->is($values, ['custom_from' => '2008-01-01', 'custom_to' => '2009-01-01'], '->clean() returns the from and to values for custom field names');
