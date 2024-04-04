<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class sfException extends Exception
{
}

sfConfig::set('sf_charset', 'UTF-8');

$t = new lime_test(11);

$a = ['<strong>escaped!</strong>', 1, null, [2, '<strong>escaped!</strong>']];
$escaped = sfOutputEscaper::escape('esc_entities', $a);

// ->getRaw()
$t->diag('->getRaw()');
$t->is($escaped->getRaw(0), '<strong>escaped!</strong>', '->getRaw() returns the raw value');

// ArrayAccess interface
$t->diag('ArrayAccess interface');
$t->is($escaped[0], '&lt;strong&gt;escaped!&lt;/strong&gt;', 'The escaped object behaves like an array');
$t->is($escaped[2], null, 'The escaped object behaves like an array');
$t->is($escaped[3][1], '&lt;strong&gt;escaped!&lt;/strong&gt;', 'The escaped object behaves like an array');

$t->ok(isset($escaped[1]), 'The escaped object behaves like an array (isset)');

$t->diag('ArrayAccess interface is read only');

try {
    unset($escaped[0]);
    $t->fail('The escaped object is read only (unset)');
} catch (sfException $e) {
    $t->pass('The escaped object is read only (unset)');
}

try {
    $escaped[0] = 12;
    $t->fail('The escaped object is read only (set)');
} catch (sfException $e) {
    $t->pass('The escaped object is read only (set)');
}

// Iterator interface
$t->diag('Iterator interface');
foreach ($escaped as $key => $value) {
    switch ($key) {
        case 0:
            $t->is($value, '&lt;strong&gt;escaped!&lt;/strong&gt;', 'The escaped object behaves like an array');

            break;

        case 1:
            $t->is($value, 1, 'The escaped object behaves like an array');

            break;

        case 2:
            $t->is($value, null, 'The escaped object behaves like an array');

            break;

        case 3:
            break;

        default:
            $t->fail('The escaped object behaves like an array');
    }
}

// ->valid()
$t->diag('->valid()');

$escaped = sfOutputEscaper::escape('esc_entities', [1, 2, 3]);
$t->is($escaped->valid(), true, '->valid() returns true if called before iteration');
