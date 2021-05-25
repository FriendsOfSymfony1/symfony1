<?php

$app = 'frontend';
$fixtures = 'fixtures/fixtures.yml';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new lime_test(3);

// ->clean()
$t->diag('->clean()');

$query = Doctrine_Core::getTable('Author')->createQuery();
$validator = new sfValidatorDoctrineChoice(array('model' => 'Author', 'query' => $query));

$author = Doctrine_Core::getTable('Author')->createQuery()->limit(1)->fetchOne();
$validator->clean($author->id);

$t->is(trim($query->getDql()), 'FROM Author', '->clean() does not change the supplied query object');

$t->info('Presenting associative array to clean() function should throw error');
$validator = new sfValidatorDoctrineChoice(array(
  'model' => 'Author',
  'multiple' => true,
));
$validator->setMessage('invalid_keys', 'invalid_keys');
try {
  $validator->clean(array('key' => 1));
  $t->fail('->clean(), presented with associative array, should throw sfValidatorError');
} catch(sfValidatorError $e) {
  $t->pass('->clean(), presented with associative array, should throw sfValidatorError');
  $t->is($e->getMessage(), 'invalid_keys', '->clean(), presented with associative array, should throw "invalid_keys" error');
}
