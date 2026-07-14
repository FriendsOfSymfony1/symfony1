<?php

require_once __DIR__.'/../../../../bootstrap/unit.php';

error_reporting(E_USER_ERROR);

$test = new lime_test(null, [
    'error_reporting' => true,
]);

trigger_error('some user error message', E_USER_ERROR);

$test->is(true, true);
