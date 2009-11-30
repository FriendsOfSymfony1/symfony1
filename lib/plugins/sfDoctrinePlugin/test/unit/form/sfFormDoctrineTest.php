<?php

$app = 'frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new lime_test(10);

// ->embedRelation()
$t->diag('->embedRelation()');

class myArticleForm extends ArticleForm
{
}

$table = Doctrine::getTable('Author');
$form = new AuthorForm($table->create(array(
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

$form = new AuthorForm($table->create(array(
  'Articles' => array(
    array('title' => 'Article 1'),
    array('title' => 'Article 2'),
  ),
)));
$form->embedRelation('Articles as author_articles');
$t->is(isset($form['author_articles']), true, '->embedRelation() embeds using an alias');
$t->is(count($form['author_articles']), 2, '->embedRelation() embeds one form for each related object using an alias');

$form = new AuthorForm($table->create(array(
  'Articles' => array(
    array('title' => 'Article 1'),
    array('title' => 'Article 2'),
  ),
)));
$form->embedRelation('Articles AS author_articles');
$t->is(isset($form['author_articles']), true, '->embedRelation() embeds using an alias with a case insensitive separator');

$form = new ArticleForm(Doctrine::getTable('Article')->create(array(
  'Author' => array('name' => 'John Doe'),
)));
$form->embedRelation('Author');
$t->is(isset($form['Author']), true, '->embedRelation() embeds a ONE type relation');
$t->is(isset($form['Author']['name']), true, '->embedRelation() embeds a ONE type relation');
$t->is($form['Author']['name']->getValue(), 'John Doe', '->embedRelation() uses values from the related object');
