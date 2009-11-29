<?php

$app = 'frontend';
$fixtures = 'fixtures/pager.yml';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new lime_test(4);

// ->getResults()
$t->diag('->getResults()');

$query = Doctrine_Core::getTable('Article')->createQuery();
$query->setHydrationMode(Doctrine_Core::HYDRATE_NONE);
$pager = new sfDoctrinePager('Article', 10);
$pager->setQuery($query);
$pager->init();
$t->isa_ok($pager->getResults(), 'array', '->getResults() uses the hydration mode set on the query');

// ->getNbResults()
$t->diag('->getNbResults()');

$pager = new sfDoctrinePager('Article', 10);
$pager->init();
$count = Doctrine_Core::getTable('Article')->createQuery()->count();
$t->is($pager->getNbResults(), $count, '->getNbResults() returns the number of results');

// Countable interface
$t->diag('Countable interface');

$pager = new sfDoctrinePager('Article', 10);
$pager->init();
$t->is(count($pager), $pager->getNbResults(), '"Countable" interface returns the total number of objects');

// Iterator interface
$t->diag('Iterator interface');

$pager = new sfDoctrinePager('Article', 10);
$pager->init();
$normal = 0;
$iterated = 0;
foreach ($pager->getResults() as $object)
{
  $normal++;
}
foreach ($pager as $object)
{
  $iterated++;
}
$t->is($iterated, $normal, '"Iterator" interface loops over objects in the current pager');
