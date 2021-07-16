<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'cache';
$debug = false;

if (!include __DIR__.'/../bootstrap/functional.php')
{
  return;
}

$b = new sfTestBrowser();

// Test for 404 support
$b->
  get('/cache/error404')->
  with('request')->begin()->
    isParameter('module', 'cache')->
    isParameter('action', 'error404')->
  end()->
  with('response')->begin()->
    isStatusCode(404)->
    checkElement('body', '/Error 404/')->
  end()->
  with('view_cache')->begin()->
    isCached(true, true)->
  end()->

  setHttpHeader('If-Modified-Since', $b->getResponse()->getHttpHeader('Last-Modified'))->

  get('/cache/error404')->
  with('response')->begin()->
    isStatusCode(304)->
    matches('/^$/')-> // Response content is empty.
  end()
;

// Test for 404 support without layout
$b->
  restart()->
  get('/cache/error404WithoutLayout')->
  with('request')->begin()->
    isParameter('module', 'cache')->
    isParameter('action', 'error404WithoutLayout')->
  end()->
  with('response')->begin()->
    isStatusCode(404)->
    checkElement('body', '/Error 404/')->
  end()->
  with('view_cache')->begin()->
    isCached(true)->
  end()->

  setHttpHeader('If-Modified-Since', $b->getResponse()->getHttpHeader('Last-Modified'))->

  get('/cache/error404WithoutLayout')->
  with('response')->begin()->
    isStatusCode(404)->
    checkElement('body', '/Error 404/')->
  end()
;
