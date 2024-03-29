<?php

require_once __DIR__.'/../../../../bootstrap/unit.php';

$test = new lime_test();

throw new LogicException('some exception message');

$test->is(true, true);
