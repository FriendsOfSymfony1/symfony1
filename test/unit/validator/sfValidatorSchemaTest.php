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

$t = new \lime_test(94);

class PreValidator extends \sfValidatorBase
{
    protected function doClean($values)
    {
        if (isset($values['s1'], $values['s2'])) {
            throw new \sfValidatorError($this, 's1_or_s2', ['value' => $values]);
        }

        return array_map('strtoupper', $values);
    }
}

class PostValidator extends \sfValidatorBase
{
    protected function doClean($values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = "*{$value}*";
        }

        return $values;
    }
}

class Post1Validator extends \sfValidatorBase
{
    protected function doClean($values)
    {
        if ($values['s1'] == $values['s2']) {
            throw new \sfValidatorError($this, 's1_not_equal_s2', ['value' => $values]);
        }
    }
}

class BytesValidatorSchema extends \sfValidatorSchema
{
    public function getBytes($value)
    {
        return parent::getBytes($value);
    }
}

$v1 = new \sfValidatorString(['max_length' => 3]);
$v2 = new \sfValidatorString(['min_length' => 3]);

// __construct()
$t->diag('__construct()');
$v = new \sfValidatorSchema();
$t->is($v->getFields(), [], '->__construct() can take no argument');
$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$t->is($v->getFields(), ['s1' => $v1, 's2' => $v2], '->__construct() can take an array of named sfValidator objects');

try {
    $v = new \sfValidatorSchema('string');
    $t->fail('__construct() throws an InvalidArgumentException when passing a non supported first argument');
} catch (\InvalidArgumentException $e) {
    $t->pass('__construct() throws an InvalidArgumentException when passing a non supported first argument');
}

// implements ArrayAccess
$t->diag('implements ArrayAccess');
$v = new \sfValidatorSchema();
$v['s1'] = $v1;
$v['s2'] = $v2;
$t->is($v->getFields(), ['s1' => $v1, 's2' => $v2], 'sfValidatorSchema implements the ArrayAccess interface for the fields');

try {
    $v['v1'] = 'string';
    $t->fail('sfValidatorSchema implements the ArrayAccess interface for the fields');
} catch (\InvalidArgumentException $e) {
    $t->pass('sfValidatorSchema implements the ArrayAccess interface for the fields');
}

$v = new \sfValidatorSchema(['s1' => $v1]);
$t->is(isset($v['s1']), true, 'sfValidatorSchema implements the ArrayAccess interface for the fields');
$t->is(isset($v['s2']), false, 'sfValidatorSchema implements the ArrayAccess interface for the fields');

$v = new \sfValidatorSchema(['s1' => $v1]);
$t->ok($v['s1'] == $v1, 'sfValidatorSchema implements the ArrayAccess interface for the fields');
$t->is($v['s2'], null, 'sfValidatorSchema implements the ArrayAccess interface for the fields');

$v = new \sfValidatorSchema(['v1' => $v1]);
unset($v['s1']);
$t->is($v['s1'], null, 'sfValidatorSchema implements the ArrayAccess interface for the fields');

// ->configure()
$t->diag('->configure()');
$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$t->is($v->getOption('allow_extra_fields'), false, '->configure() sets "allow_extra_fields" option to false by default');
$t->is($v->getOption('filter_extra_fields'), true, '->configure() sets "filter_extra_fields" option to true by default');
$t->is($v->getMessage('extra_fields'), 'Unexpected extra form field named "%field%".', '->configure() has a default error message for the "extra_fields" error');

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2], ['allow_extra_fields' => true, 'filter_extra_fields' => false], ['extra_fields' => 'Extra fields']);
$t->is($v->getOption('allow_extra_fields'), true, '->__construct() can override the default value for the "allow_extra_fields" option');
$t->is($v->getOption('filter_extra_fields'), false, '->__construct() can override the default value for the "filter_extra_fields" option');

$t->is($v->getMessage('extra_fields'), 'Extra fields', '->__construct() can override the default message for the "extra_fields" error message');

// ->clean()
$t->diag('->clean()');

$v = new \sfValidatorSchema();
$t->is($v->clean(null), [], '->clean() converts null to empty array before validation');

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);

try {
    $v->clean('foo');
    $t->fail('->clean() throws an InvalidArgumentException exception if the first argument is not an array of value');
} catch (\InvalidArgumentException $e) {
    $t->pass('->clean() throws an InvalidArgumentException exception if the first argument is not an array of value');
}

$t->is($v->clean(['s1' => 'foo', 's2' => 'bar']), ['s1' => 'foo', 's2' => 'bar'], '->clean() returns the string unmodified');

try {
    $v->clean(['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if a you give a non existant field');
    $t->skip('', 2);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if a you give a non existant field');
    $t->is(count($e), 1, '->clean() throws an exception with all error messages');
    $t->is($e[0]->getCode(), 'extra_fields', '->clean() throws an exception with all error messages');
}

$t->diag('required fields');

try {
    $v->clean(['s1' => 'foo']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if a required field is not provided');
    $t->skip('', 2);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if a required field is not provided');
    $t->is(count($e), 1, '->clean() throws an exception with all error messages');
    $t->is($e['s2']->getCode(), 'required', '->clean() throws an exception with all error messages');
}

// ->getPreValidator() ->setPreValidator()
$t->diag('->getPreValidator() ->setPreValidator()');
$v1 = new \sfValidatorString(['max_length' => 3, 'required' => false]);
$v2 = new \sfValidatorString(['min_length' => 3, 'required' => false]);
$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$v->setPreValidator($preValidator = new \PreValidator());
$t->ok($v->getPreValidator() == $preValidator, '->getPreValidator() returns the current pre validator');

try {
    $v->clean(['s1' => 'foo', 's2' => 'bar']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if a pre-validator fails');
    $t->skip('', 2);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if a pre-validator fails');
    $t->is(count($e), 1, '->clean() throws an exception with all error messages');
    $t->is($e[0]->getCode(), 's1_or_s2', '->clean() throws an exception with all error messages');
}

$s = $v->clean(['s1' => 'foo']);
$t->is('FOO', $s['s1'], '->clean() takes values returned by pre-validator');

// ->getPostValidator() ->setPostValidator()
$t->diag('->getPostValidator() ->setPostValidator()');
$v1 = new \sfValidatorString(['max_length' => 3, 'required' => false]);
$v2 = new \sfValidatorString(['min_length' => 3, 'required' => false]);
$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$v->setPostValidator($postValidator = new \PostValidator());
$t->ok($v->getPostValidator() == $postValidator, '->getPostValidator() returns the current post validator');
$t->is($v->clean(['s1' => 'foo', 's2' => 'bar']), ['s1' => '*foo*', 's2' => '*bar*'], '->clean() executes post validators');

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$v->setPostValidator(new \Post1Validator());

try {
    $v->clean(['s1' => 'foo', 's2' => 'foo']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if a post-validator fails');
    $t->skip('', 2);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if a post-validator fails');
    $t->is(count($e), 1, '->clean() throws an exception with all error messages');
    $t->is($e[0]->getCode(), 's1_not_equal_s2', '->clean() throws an exception with all error messages');
}

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$t->is($v->clean(['s1' => 'foo']), ['s1' => 'foo', 's2' => null], '->clean() returns null values for fields not present in the input array');

$t->diag('extra fields');
$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2]);
$v->setOption('allow_extra_fields', true);
$ret = $v->clean(['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar']);
$t->is($ret, ['s1' => 'foo', 's2' => 'bar'], '->clean() filters non existant fields if "allow_extra_fields" is true');

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2], ['allow_extra_fields' => true]);
$ret = $v->clean(['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar']);
$t->is($ret, ['s1' => 'foo', 's2' => 'bar'], '->clean() filters non existant fields if "allow_extra_fields" is true');

$v = new \sfValidatorSchema(['s1' => $v1, 's2' => $v2], ['allow_extra_fields' => true, 'filter_extra_fields' => false]);
$ret = $v->clean(['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar']);
$t->is($ret, ['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar'], '->clean() do not filter non existant fields if "filter_extra_fields" is false');

$v->setOption('filter_extra_fields', false);
$ret = $v->clean(['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar']);
$t->is($ret, ['s1' => 'foo', 's2' => 'bar', 'foo' => 'bar'], '->clean() do not filter non existant fields if "filter_extra_fields" is false');

$t->diag('one validator fails');
$v['s2']->setOption('max_length', 2);

try {
    $v->clean(['s1' => 'foo', 's2' => 'bar']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if one of the validators fails');
    $t->skip('', 2);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if one of the validators fails');
    $t->is(count($e), 1, '->clean() throws an exception with all error messages');
    $t->is($e['s2']->getCode(), 'max_length', '->clean() throws an exception with all error messages');
}

$t->diag('several validators fail');
$v['s1']->setOption('max_length', 2);
$v['s2']->setOption('max_length', 2);

try {
    $v->clean(['s1' => 'foo', 's2' => 'bar']);
    $t->fail('->clean() throws an sfValidatorErrorSchema exception if one of the validators fails');
    $t->skip('', 3);
} catch (\sfValidatorErrorSchema $e) {
    $t->pass('->clean() throws an sfValidatorErrorSchema exception if one of the validators fails');
    $t->is(count($e), 2, '->clean() throws an exception with all error messages');
    $t->is($e['s2']->getCode(), 'max_length', '->clean() throws an exception with all error messages');
    $t->is($e['s1']->getCode(), 'max_length', '->clean() throws an exception with all error messages');
}

$t->diag('postValidator can throw named errors or global errors');
$comparator = new \sfValidatorSchemaCompare('left', \sfValidatorSchemaCompare::EQUAL, 'right');
$userValidator = new \sfValidatorSchema([
    'test' => new \sfValidatorString(['min_length' => 10]),
    'left' => new \sfValidatorString(['min_length' => 2]),
    'right' => new \sfValidatorString(['min_length' => 2]),
]);
$userValidator->setPostValidator($comparator);
$v = new \sfValidatorSchema([
    'test' => new \sfValidatorString(['min_length' => 10]),
    'left' => new \sfValidatorString(['min_length' => 2]),
    'right' => new \sfValidatorString(['min_length' => 2]),
    'embedded' => $userValidator,
]);
$v->setPostValidator($comparator);

$t->diag('postValidator throws global errors');
foreach ([$userValidator->getPostValidator(), $v->getPostValidator(), $v['embedded']->getPostValidator()] as $validator) {
    $validator->setOption('throw_global_error', true);
}

try {
    $v->clean(['test' => 'fabien', 'right' => 'bar', 'embedded' => ['test' => 'fabien', 'left' => 'oof', 'right' => 'rab']]);
    $t->skip('', 7);
} catch (\sfValidatorErrorSchema $e) {
    $t->is(count($e->getNamedErrors()), 3, '->clean() throws an exception with all error messages');
    $t->is(count($e->getGlobalErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(count($e['embedded']->getNamedErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(count($e['embedded']->getGlobalErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(isset($e['left']) ? $e['left']->getCode() : '', 'required', '->clean() throws an exception with all error messages');
    $t->is(isset($e['embedded']['left']) ? $e['embedded']['left']->getCode() : '', '', '->clean() throws an exception with all error messages');
    $t->is($e->getCode(), 'invalid test [min_length] embedded [invalid test [min_length]] left [required]', '->clean() throws an exception with all error messages');
}

$t->diag('postValidator throws named errors');
foreach ([$userValidator->getPostValidator(), $v->getPostValidator(), $v['embedded']->getPostValidator()] as $validator) {
    $validator->setOption('throw_global_error', false);
}

try {
    $v->clean(['test' => 'fabien', 'right' => 'bar', 'embedded' => ['test' => 'fabien', 'left' => 'oof', 'right' => 'rab']]);
    $t->skip('', 7);
} catch (\sfValidatorErrorSchema $e) {
    $t->is(count($e->getNamedErrors()), 3, '->clean() throws an exception with all error messages');
    $t->is(count($e->getGlobalErrors()), 0, '->clean() throws an exception with all error messages');
    $t->is(count($e['embedded']->getNamedErrors()), 2, '->clean() throws an exception with all error messages');
    $t->is(count($e['embedded']->getGlobalErrors()), 0, '->clean() throws an exception with all error messages');
    $t->is(isset($e['left']) ? $e['left']->getCode() : '', 'required invalid', '->clean() throws an exception with all error messages');
    $t->is(isset($e['embedded']['left']) ? $e['embedded']['left']->getCode() : '', 'invalid', '->clean() throws an exception with all error messages');
    $t->is($e->getCode(), 'test [min_length] embedded [test [min_length] left [invalid]] left [required invalid]', '->clean() throws an exception with all error messages');
}

$t->diag('complex postValidator');
$comparator1 = new \sfValidatorSchemaCompare('password', \sfValidatorSchemaCompare::EQUAL, 'password_bis');
$v = new \sfValidatorSchema([
    'left' => new \sfValidatorString(['min_length' => 2]),
    'right' => new \sfValidatorString(['min_length' => 2]),
    'password' => new \sfValidatorString(['min_length' => 2]),
    'password_bis' => new \sfValidatorString(['min_length' => 2]),
]);
$v->setPostValidator(new \sfValidatorAnd([$comparator, $comparator1]));

try {
    $v->clean(['left' => 'foo', 'right' => 'bar', 'password' => 'oof', 'password_bis' => 'rab']);
    $t->skip('', 3);
} catch (\sfValidatorErrorSchema $e) {
    $t->is(count($e->getNamedErrors()), 2, '->clean() throws an exception with all error messages');
    $t->is(count($e->getGlobalErrors()), 0, '->clean() throws an exception with all error messages');
    $t->is($e->getCode(), 'left [invalid] password [invalid]', '->clean() throws an exception with all error messages');
}

$comparator->setOption('throw_global_error', true);

try {
    $v->clean(['left' => 'foo', 'right' => 'bar', 'password' => 'oof', 'password_bis' => 'rab']);
    $t->skip('', 3);
} catch (\sfValidatorErrorSchema $e) {
    $t->is(count($e->getNamedErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(count($e->getGlobalErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is($e->getCode(), 'invalid password [invalid]', '->clean() throws an exception with all error messages');
}

$userValidator = new \sfValidatorSchema([
    'left' => new \sfValidatorString(['min_length' => 2]),
    'right' => new \sfValidatorString(['min_length' => 2]),
    'password' => new \sfValidatorString(['min_length' => 2]),
    'password_bis' => new \sfValidatorString(['min_length' => 2]),
]);
$userValidator->setPostValidator(new \sfValidatorAnd([$comparator, $comparator1]));
$v = new \sfValidatorSchema([
    'left' => new \sfValidatorString(['min_length' => 2]),
    'right' => new \sfValidatorString(['min_length' => 2]),
    'password' => new \sfValidatorString(['min_length' => 2]),
    'password_bis' => new \sfValidatorString(['min_length' => 2]),
    'user' => $userValidator,
]);
$v->setPostValidator(new \sfValidatorAnd([$comparator, $comparator1]));

try {
    $v->clean(['left' => 'foo', 'right' => 'bar', 'password' => 'oof', 'password_bis' => 'rab', 'user' => ['left' => 'foo', 'right' => 'bar', 'password' => 'oof', 'password_bis' => 'rab']]);
    $t->skip('', 7);
} catch (\sfValidatorErrorSchema $e) {
    $t->is(count($e->getNamedErrors()), 2, '->clean() throws an exception with all error messages');
    $t->is(count($e->getGlobalErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(count($e['user']->getNamedErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(count($e['user']->getGlobalErrors()), 1, '->clean() throws an exception with all error messages');
    $t->is(isset($e['user']) ? $e['user']->getCode() : '', 'invalid password [invalid]', '->clean() throws an exception with all error messages');
    $t->is(isset($e['user']['password']) ? $e['user']['password']->getCode() : '', 'invalid', '->clean() throws an exception with all error messages');
    $t->is($e->getCode(), 'invalid user [invalid password [invalid]] password [invalid]', '->clean() throws an exception with all error messages');
}

// __clone()
$t->diag('__clone()');
$v = new \sfValidatorSchema(['v1' => $v1, 'v2' => $v2]);
$v1 = clone $v;
$f1 = $v1->getFields();
$f = $v->getFields();
$t->is(array_keys($f1), array_keys($f), '__clone() clones embedded validators');
foreach ($f1 as $name => $validator) {
    $t->ok($validator !== $f[$name], '__clone() clones embedded validators');
    $t->ok($validator == $f[$name], '__clone() clones embedded validators');
}
$t->is($v1->getPreValidator(), null, '__clone() clones the pre validator');
$t->is($v1->getPostValidator(), null, '__clone() clones the post validator');

$v->setPreValidator(new \sfValidatorString(['min_length' => 4]));
$v->setPostValidator(new \sfValidatorString(['min_length' => 4]));
$v1 = clone $v;
$t->ok($v1->getPreValidator() !== $v->getPreValidator(), '__clone() clones the pre validator');
$t->ok($v1->getPreValidator() == $v->getPreValidator(), '__clone() clones the pre validator');
$t->ok($v1->getPostValidator() !== $v->getPostValidator(), '__clone() clones the post validator');
$t->ok($v1->getPostValidator() == $v->getPostValidator(), '__clone() clones the post validator');

$t->diag('convert post_max_size to bytes');
$v = new \BytesValidatorSchema();
$t->is($v->getBytes(null), 0, 'empty string considered as 0 bytes');
$t->is($v->getBytes(''), 0, 'empty string considered as 0 bytes');
$t->is($v->getBytes('0'), 0, 'simple bytes');
$t->is($v->getBytes('1'), 1, 'simple bytes');
$t->is($v->getBytes('1B'), 1, 'simple bytes');
$t->is($v->getBytes('1K'), 1024, 'kilobytes');
$t->is($v->getBytes('1M'), 1024 * 1024, 'megabytes short syntax');
$t->is($v->getBytes('0.5M'), 1024 * 1024 / 2, 'fractional megabytes');
$t->is($v->getBytes('1G'), 1024 * 1024 * 1024, 'gigabytes');
