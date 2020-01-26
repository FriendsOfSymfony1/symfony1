<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
require_once(__DIR__.'/../bootstrap/functional.php');

$t = new lime_test(13);

$article = new Article();
$article->title = 'test';
$t->is($article->Translation['en']->title, 'test');

sfContext::getInstance()->getUser()->setCulture('fr');
$article->title = 'fr test';
$t->is($article->Translation['fr']->title, 'fr test');

$t->is($article->getTitle(), $article->title);
$article->setTitle('test');
$t->is($article->getTitle(), 'test');

$article->setTestColumn('test');
$t->is($article->getTestColumn(), 'test');
$t->is($article->Translation['fr']['test_column'], 'test');

$article->free(true);

class MyArticleForm extends ArticleForm
{
  public function configure()
  {
    parent::configure();

    $this->embedI18n(array('en', 'fr'));

    $authorForm = new AuthorForm($this->object->Author);
    unset($authorForm['id']);

    $this->embedForm('Author', $authorForm);

    unset($this['author_id']);
  }
}

$article = new Article();
$articleForm = new MyArticleForm($article);

$data = array(
  'is_on_homepage' => 1,
  'Author' => array(
    'name' => 'i18n author test',
    'type' => null),
  'en' => array(
    'title' => 'english title',
    'body'  => 'english body'),
  'fr' => array(
    'title' => 'french title',
    'body'  => 'french body'),
  'created_at' => time(),
  'updated_at' => time(),
);

$articleForm->bind($data);
$t->is($articleForm->isValid(), true);

$data = $articleForm->getValues();

$values = array(
  'is_on_homepage' => true,
  'Author' => 
  array(
    'name' => 'i18n author test',
    'type' => null
  ),
  'en' => 
  array(
    'title' => 'english title',
    'body' => 'english body',
    'test_column' => '',
    'slug' => '',
  ),
  'fr' => 
  array(
    'title' => 'french title',
    'body' => 'french body',
    'test_column' => '',
    'slug' => '',
  ),
  'id' => null,
  'type' => null,
  'views' => null,
  'created_at' => $data['created_at'],
  'updated_at' => $data['updated_at'],
);

$t->is($articleForm->getValues(), $values);

$articleForm->save();

$expected = array(
  'id' => $article->id,
  'author_id' => $article->Author->id,
  'is_on_homepage' => true,
  'type' => null,
  'views' => null,
  'created_at' => $article->created_at,
  'updated_at' => $article->updated_at,
  'Translation' => 
  array(
    'en' => 
    array(
      'id' => $article->id,
      'title' => 'english title',
      'body' => 'english body',
      'test_column' => '',
      'lang' => 'en',
      'slug' => 'english-title',
    ),
    'fr' => 
    array(
      'id' => $article->id,
      'title' => 'french title',
      'body' => 'french body',
      'test_column' => '',
      'lang' => 'fr',
      'slug' => 'french-title',
    ),
  ),
  'Author' => 
  array(
    'id' => $article->Author->id,
    'name' => 'i18n author test',
    'type' => null
  ),
);

$t->is($article->toArray(true), $expected);

$articleForm = new MyArticleForm($article);

$expected = array(
  'id' => $article->id,
  'author_id' => $article->author_id,
  'is_on_homepage' => true,
  'type' => null,
  'views' => null,
  'created_at' => $article->created_at,
  'updated_at' => $article->updated_at,
  'en' => 
  array(
    'id' => $article->id,
    'title' => 'english title',
    'body' => 'english body',
    'test_column' => '',
    'lang' => 'en',
    'slug' => 'english-title',
  ),
  'fr' => 
  array(
    'id' => $article->id,
    'title' => 'french title',
    'body' => 'french body',
    'test_column' => '',
    'lang' => 'fr',
    'slug' => 'french-title',
  ),
  'Author' => 
  array(
    'id' => $article->Author->id,
    'name' => 'i18n author test',
    'type' => null
  ),
);

$t->is($articleForm->getDefaults(), $expected);

// Bug #7486
$data = array(
  'id' => $article->id,
  'is_on_homepage' => true,
  'type' => null,
  'created_at' => $article->created_at,
  'updated_at' => $article->updated_at,
  'en' =>
  array(
    'id' => $article->id,
    'title' => 'english title',
    'body' => 'english body',
    'test_column' => '',
    'lang' => 'en',
    'slug' => 'english-title',
  ),
  'fr' =>
  array(
    'id' => $article->id,
    'title' => 'french title',
    'body' => 'french body',
    'test_column' => '',
    'lang' => 'fr',
    'slug' => 'french-title',
  ),
  'Author' =>
  array(
    'name' => 'i18n author test',
    'type' => null
  ),
);

$articleForm->bind($data);
$t->is($articleForm->isValid(), true);

$article = new Article();
$articleForm = new MyArticleForm($article);

$data = array(
  'is_on_homepage' => 1,
  'Author' => array(
    'name' => 'i18n author test',
    'type' => null),
  'en' => array(
    'title' => 'english title',
    'body'  => 'english body'),
  'fr' => array(
    'title' => 'french title',
    'body'  => 'french body'),
  'created_at' => time(),
  'updated_at' => time(),
);

$articleForm->bind($data);
$t->is($articleForm->isValid(), false);
// END: Bug #7486

$article = new Article();
sfContext::getInstance()->getUser()->setCulture('en');
$article->title = 'test';
sfContext::getInstance()->getUser()->setCulture('fr');
$t->is($article->title, 'test');