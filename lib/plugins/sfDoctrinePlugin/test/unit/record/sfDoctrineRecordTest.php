<?php

$app = 'frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new lime_test(6);

// ->__construct()
$t->diag('->__construct()');

function is_symfony_i18n_filter($filter)
{
  return $filter instanceof sfDoctrineRecordI18nFilter;
}

$table = Doctrine_Core::getTable('Article');
new Article();
$t->is(count(array_filter($table->getFilters(), 'is_symfony_i18n_filter')), 1, '->__construct() adds i18n filter');
new Article();
$t->is(count(array_filter($table->getFilters(), 'is_symfony_i18n_filter')), 1, '->__construct() adds i18n filter once');

// ->serialize() ->unserialize()
$t->diag('->serialize() ->unserialize()');

$before = new Author();
$before->name = 'test';
$serialized = serialize($before);
$after = unserialize($serialized);
$t->is($after->name, 'test', '->unserialize() maintains field values');

$conn = Doctrine_Manager::getInstance()->getConnectionForComponent('Author');
$before = new Author();
$before->name = 'test';
$serialized = serialize($before);
$conn->clear();
$conn->evictTables();
$after = unserialize($serialized);
$t->is($after->name, 'test', '->unserialize() maintains field values upon reset');

$before = new Article();
$before->title = 'test';
$serialized = serialize($before);
$after = unserialize($serialized);
$t->is($after->title, 'test', '->unserialize() maintains field values on I18n records');

$conn = Doctrine_Manager::getInstance()->getConnectionForComponent('Article');
$before = new Article();
$before->title = 'test';
$serialized = serialize($before);
$conn->clear();
$conn->evictTables();
$after = unserialize($serialized);
$t->is($after->title, 'test', '->unserialize() maintains field values on I18n records upon reset');
