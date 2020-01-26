<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
$fixtures = 'fixtures';
require_once(__DIR__.'/../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->get('/articles/redirectToShow')
  ->with('response')->begin()
    ->isRedirected()
  ->end()
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'articles')
    ->isParameter('action', 'show')
  ->end()
;
