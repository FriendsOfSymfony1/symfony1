<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

error_reporting(error_reporting() & ~E_STRICT);

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new \lime_test(3);

@include_once 'PEAR.php';
if (!class_exists('PEAR')) {
    $t->skip('PEAR must be installed', 3);

    return;
}

require_once __DIR__.'/sfPearDownloaderTest.class.php';

require_once __DIR__.'/sfPearRestTest.class.php';

require_once __DIR__.'/sfPluginTestHelper.class.php';

// setup
$temp = tempnam('/tmp/sf_plugin_test', 'tmp');
unlink($temp);
mkdir($temp, 0777, true);

define('SF_PLUGIN_TEST_DIR', $temp);

$options = [
    'plugin_dir' => $temp.'/plugins',
    'cache_dir' => $temp.'/cache',
    'preferred_state' => 'stable',
    'rest_base_class' => 'sfPearRestTest',
    'downloader_base_class' => 'sfPearDownloaderTest',
];

$dispatcher = new \sfEventDispatcher();

// ->initialize()
$t->diag('->initialize()');

foreach (['plugin_dir', 'cache_dir'] as $option) {
    try {
        $localOptions = $options;
        unset($localOptions[$option]);
        $environment = new \sfPearEnvironment($dispatcher, $localOptions);

        $t->fail(sprintf('->initialize() throws an exception if you don\'t pass a "%s" option', $option));
    } catch (\sfException $e) {
        $t->pass(sprintf('->initialize() throws an exception if you don\'t pass a "%s" option', $option));
    }
}

// ->registerChannel()
$t->diag('->registerChannel()');
$environment = new \sfPearEnvironment($dispatcher, $options);
$environment->registerChannel('pear.example.com', true);
$t->pass('->registerChannel() registers a PEAR channel');

// teardown
\sfToolkit::clearDirectory($temp);
rmdir($temp);
