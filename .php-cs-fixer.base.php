<?php

if (!file_exists(__DIR__.'/lib')) {
    exit(0);
}

$fixer = (new PhpCsFixer\Config())
    ->setRules([
        'no_spaces_after_function_name' => true,
        'no_spaces_inside_parenthesis' => true,
    ])
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache')
;

return $fixer;