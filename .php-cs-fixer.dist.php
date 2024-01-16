<?php

$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/data/bin')
    ->in(__DIR__.'/test')
    ->append([__FILE__])
    // Exclude PHP classes templates/generators, which are not valid PHP files
    ->exclude('task/generator/skeleton/')
    ->exclude('plugins/sfDoctrinePlugin/data/generator/')
    // Exclude generated files (single files)
    ->notPath('unit/config/fixtures/sfDefineEnvironmentConfigHandler/prefix_result.php')
    ->notPath('unit/config/fixtures/sfFilterConfigHandler/result.php')
;

$config = new PhpCsFixer\Config();
$config->setRules([
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
])
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
