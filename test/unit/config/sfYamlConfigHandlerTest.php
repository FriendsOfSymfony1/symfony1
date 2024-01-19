<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new \lime_test(7);

class myConfigHandler extends \sfYamlConfigHandler
{
    public $yamlConfig;

    public function execute($configFiles)
    {
    }

    public static function parseYamls($configFiles)
    {
        return parent::parseYamls($configFiles);
    }

    public static function parseYaml($configFile)
    {
        return parent::parseYaml($configFile);
    }

    public function mergeConfigValue($keyName, $category)
    {
        return parent::mergeConfigValue($keyName, $category);
    }

    public function getConfigValue($keyName, $category, $defaultValue = null)
    {
        return parent::getConfigValue($keyName, $category, $defaultValue);
    }
}

$config = new \myConfigHandler();
$config->initialize();

// ->parseYamls()
$t->diag('->parseYamls()');

// ->parseYaml()
$t->diag('->parseYaml()');

// ->mergeConfigValue()
$t->diag('->mergeConfigValue()');
$config->yamlConfig = [
    'bar' => [
        'foo' => [
            'foo' => 'foobar',
            'bar' => 'bar',
        ],
    ],
    'all' => [
        'foo' => [
            'foo' => 'fooall',
            'barall' => 'barall',
        ],
    ],
];
$values = $config->mergeConfigValue('foo', 'bar');
$t->is($values['foo'], 'foobar', '->mergeConfigValue() merges values for a given key under a given category');
$t->is($values['bar'], 'bar', '->mergeConfigValue() merges values for a given key under a given category');
$t->is($values['barall'], 'barall', '->mergeConfigValue() merges values for a given key under a given category');

// ->getConfigValue()
$t->diag('->getConfigValue()');
$config->yamlConfig = [
    'bar' => [
        'foo' => 'foobar',
    ],
    'all' => [
        'foo' => 'fooall',
    ],
];
$t->is($config->getConfigValue('foo', 'bar'), 'foobar', '->getConfigValue() returns the value for the key in the given category');
$t->is($config->getConfigValue('foo', 'all'), 'fooall', '->getConfigValue() returns the value for the key in the given category');
$t->is($config->getConfigValue('foo', 'foofoo'), 'fooall', '->getConfigValue() returns the value for the key in the "all" category if the key does not exist in the given category');
$t->is($config->getConfigValue('foofoo', 'foofoo', 'default'), 'default', '->getConfigValue() returns the default value if key is not found in the category and in the "all" category');
