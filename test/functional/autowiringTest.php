<?php

$app = 'frontend';
if (!include(__DIR__ . '/../bootstrap/functional.php')) {
  return;
}

$b = new sfTestBrowser();

// test action execution
$b->
  get('/autowiring/index')->
  with('request')->begin()->
    isParameter('module', 'autowiring')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('div[id=value]', 'success')->
  end()
;

// test component execution
$b->
  get('/autowiring/withComponents')->
  with('request')->begin()->
    isParameter('module', 'autowiring')->
    isParameter('action', 'withComponents')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('div[id=component_value]', 'success')->
  end()
;
