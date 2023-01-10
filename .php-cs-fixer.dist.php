<?php

if (!file_exists(__DIR__.'/lib')) {
    exit(0);
}

$fixer = require __DIR__ .'/.php-cs-fixer.base.php';

$fixer->setFinder((new PhpCsFixer\Finder())
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/tests')
    ->append([__FILE__]));

return $fixer;