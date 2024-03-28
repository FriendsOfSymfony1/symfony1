<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please generator the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(12);

// ->isPartial() ->isComponent() ->isLink()
$t->diag('->isPartial() ->isComponent() ->isLink()');

$field = new sfModelGeneratorConfigurationField('my_field', []);
$t->is($field->isPartial(), false, '->isPartial() defaults to false');
$t->is($field->isComponent(), false, '->isComponent() defaults to false');
$t->is($field->isLink(), false, '->isLink() defaults to false');

$field = new sfModelGeneratorConfigurationField('my_field', ['flag' => '_']);
$t->is($field->isPartial(), true, '->isPartial() returns true if flag is "_"');
$t->is($field->isComponent(), false, '->isComponent() defaults to false');
$t->is($field->isLink(), false, '->isLink() defaults to false');

$field = new sfModelGeneratorConfigurationField('my_field', ['flag' => '~']);
$t->is($field->isPartial(), false, '->isPartial() defaults to false');
$t->is($field->isComponent(), true, '->isComponent() returns true if flag is "~"');
$t->is($field->isLink(), false, '->isLink() defaults to false');

$field = new sfModelGeneratorConfigurationField('my_field', ['flag' => '=']);
$t->is($field->isPartial(), false, '->isPartial() defaults to false');
$t->is($field->isComponent(), false, '->isComponent() defaults to false');
$t->is($field->isLink(), true, '->isLink() returns true if flag is "="');
