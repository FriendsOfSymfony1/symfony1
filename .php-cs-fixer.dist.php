<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
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

$config = new Config();
$config->setRules([
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    '@PSR12' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'fully_qualified_strict_types' => [
        'import_symbols' => true,
        'leading_backslash_in_global_namespace' => true,
    ],
    'modernize_strpos' => true,
])
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
