<?php

$app = 'frontend';
$fixtures = 'fixtures/fixtures.yml';
include __DIR__.'/../../bootstrap/functional.php';

$t = new lime_test(4);

// ->getChoices()
$t->diag('->getChoices()');

$validator = new sfWidgetFormDoctrineChoice(array('model' => 'Author'));

$author = Doctrine_Core::getTable('Author')->createQuery()->limit(1)->fetchOne();

$t->is_deeply($validator->getChoices(), array(1 => 'Jonathan H. Wage', 2 => 'Fabien POTENCIER'), '->getChoices() returns choices');

$validator->setOption('order_by', array('name', 'asc'));

$t->cmp_ok($validator->getChoices(), '===', array(2 => 'Fabien POTENCIER', 1 => 'Jonathan H. Wage'), '->getChoices() returns ordered choices');

$validator->setOption('table_method', 'testTableMethod');

$t->is_deeply($validator->getChoices(), array(1 => 'Jonathan H. Wage', 2 => 'Fabien POTENCIER'), '->getChoices() returns choices for given "table_method" option');

$validator = new sfWidgetFormDoctrineChoice(array('model' => 'Author', 'query' => Doctrine_Core::getTable('Author')->createQuery()->limit(1)));

$t->is_deeply($validator->getChoices(), array(1 => 'Jonathan H. Wage'), '->getChoices() returns choices for given "query" option');
