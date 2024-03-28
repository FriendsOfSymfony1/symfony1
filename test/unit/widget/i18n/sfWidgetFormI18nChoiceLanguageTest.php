<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../bootstrap/unit.php';

$t = new lime_test(6);

$dom = new DOMDocument('1.0', 'utf-8');
$dom->validateOnParse = true;

// ->configure()
$t->diag('->configure()');

try {
    new sfWidgetFormI18nChoiceLanguage(['culture' => 'en', 'languages' => ['xx']]);
    $t->fail('->configure() throws an InvalidArgumentException if a language does not exist');
} catch (InvalidArgumentException $e) {
    $t->pass('->configure() throws an InvalidArgumentException if a language does not exist');
}

$v = new sfWidgetFormI18nChoiceLanguage(['culture' => 'en', 'languages' => ['fr', 'en']]);
$t->is(array_keys($v->getOption('choices')), ['en', 'fr'], '->configure() can restrict the number of languages with the languages option');

// ->render()
$t->diag('->render()');
$w = new sfWidgetFormI18nChoiceLanguage(['culture' => 'fr']);
$dom->loadHTML($w->render('language', 'en'));
$css = new sfDomCssSelector($dom);
$t->is($css->matchSingle('#language option[value="en"]')->getValue(), 'anglais', '->render() renders all languages as option tags');
$t->is(count($css->matchAll('#language option[value="en"][selected="selected"]')->getNodes()), 1, '->render() renders all languages as option tags');

// add_empty
$t->diag('add_empty');
$w = new sfWidgetFormI18nChoiceLanguage(['culture' => 'fr', 'add_empty' => true]);
$dom->loadHTML($w->render('language', 'FR'));
$css = new sfDomCssSelector($dom);
$t->is($css->matchSingle('#language option[value=""]')->getValue(), '', '->render() renders an empty option if add_empty is true');

$w = new sfWidgetFormI18nChoiceLanguage(['culture' => 'fr', 'add_empty' => 'foo']);
$dom->loadHTML($w->render('language', 'FR'));
$css = new sfDomCssSelector($dom);
$t->is($css->matchSingle('#language option[value=""]')->getValue(), 'foo', '->render() renders an empty option if add_empty is true');
