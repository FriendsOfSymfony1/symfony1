<?php

$app = 'frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new lime_test(5);

// ->embedRelation()
$t->diag('->embedRelation()');

class myArticleForm extends ArticleForm
{
}

$form = new AuthorForm(Doctrine::getTable('Author')->create(array(
  'Articles' => array(
    array('title' => 'Article 1'),
    array('title' => 'Article 2'),
    array('title' => 'Article 3'),
  ),
)));

$form->embedRelation('Articles');
$embeddedForms = $form->getEmbeddedForms();

$t->ok(isset($form['Articles']), '->embedRelation() embeds forms');
$t->is(count($embeddedForms['Articles']), 3, '->embedRelation() embeds one form for each related object');

$form->embedRelation('Articles', 'myArticleForm', array(array('test' => true)));
$embeddedForms = $form->getEmbeddedForms();
$moreEmbeddedForms = $embeddedForms['Articles']->getEmbeddedForms();
$t->isa_ok($moreEmbeddedForms[0], 'myArticleForm', '->embedRelation() accepts a form class argument');
$t->ok($moreEmbeddedForms[0]->getOption('test'), '->embedRelation() accepts a form arguments argument');

$form = new ArticleForm();
try
{
  $form->embedRelation('Author');
  $t->fail('->embedRelation() throws an exception if relation is not a collection');
}
catch (InvalidArgumentException $e)
{
  $t->pass('->embedRelation() throws an exception if relation is not a collection');
}
