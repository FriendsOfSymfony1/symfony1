<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../bootstrap/unit.php';

$t = new \lime_test(3);

// ->configure()
$t->diag('->configure()');

try {
    new \sfValidatorI18nChoiceLanguage(['languages' => ['xx']]);
    $t->fail('->configure() throws an InvalidArgumentException if a language does not exist');
} catch (\InvalidArgumentException $e) {
    $t->pass('->configure() throws an InvalidArgumentException if a language does not exist');
}

$v = new \sfValidatorI18nChoiceLanguage(['languages' => ['fr', 'en']]);
$t->is($v->getOption('choices'), ['fr', 'en'], '->configure() can restrict the number of languages with the languages option');

// ->clean()
$t->diag('->clean()');
$v = new \sfValidatorI18nChoiceLanguage(['languages' => ['fr', 'en']]);
$t->is($v->clean('fr'), 'fr', '->clean() cleans the input value');
