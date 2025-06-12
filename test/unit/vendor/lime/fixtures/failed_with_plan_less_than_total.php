<?php

require_once __DIR__.'/../../../../bootstrap/unit.php';

$test = new lime_test(1);

$test->is(false, true);
$test->is(true, true);
