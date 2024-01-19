<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
$fixtures = 'fixtures/fixtures.yml';

include dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new \lime_test(1);

// ->clean()
$t->diag('->clean()');

$query = \Doctrine_Core::getTable('Author')->createQuery();
$validator = new \sfValidatorDoctrineChoice(['model' => 'Author', 'query' => $query]);

$author = \Doctrine_Core::getTable('Author')->createQuery()->limit(1)->fetchOne();
$validator->clean($author->id);

$t->is(trim($query->getDql()), 'FROM Author', '->clean() does not change the supplied query object');
