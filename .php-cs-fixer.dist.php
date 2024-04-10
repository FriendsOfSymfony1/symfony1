<?php

$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/data/bin')
    ->in(__DIR__.'/test')
    ->in(__DIR__.'/test/unit/vendor')
    ->append([__FILE__])
    // Exclude PHP classes templates/generators, which are not valid PHP files
    ->exclude('task/generator/skeleton/')
    ->exclude('plugins/sfDoctrinePlugin/data/generator/')
    ->exclude('plugins/sfDoctrinePlugin/test/functional/fixtures/')

    // Exclude sub-modules folders
    ->exclude('plugins/sfDoctrinePlugin/lib/vendor/doctrine')

    // Exclude generated files (whole directories)
    ->exclude('functional/fixtures/cache')
    ->exclude('functional/fixtures/log')

    // Exclude generated files (single files)
    ->notPath('unit/config/fixtures/sfDefineEnvironmentConfigHandler/prefix_result.php')
    ->notPath('unit/config/fixtures/sfFilterConfigHandler/result.php')

    // Exclude files with expected parsing error
    ->notPath('lime/fixtures/pass_with_one_parse_error.php')
;

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setCacheFile('.cache/php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
