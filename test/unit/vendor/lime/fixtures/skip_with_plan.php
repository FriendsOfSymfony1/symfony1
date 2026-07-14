<?php

require_once __DIR__.'/../../../../bootstrap/unit.php';

$test = new lime_test($plan = 2);

$test->skip('some skip message', $plan);
