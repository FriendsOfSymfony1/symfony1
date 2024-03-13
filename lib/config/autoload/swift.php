<?php

if (!class_exists('Swift')) {
    $swift_dir = sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/lib';
    require_once $swift_dir.'/swift_required.php';
}
