<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

if (!isset($argv[1])) {
    throw new \InvalidArgumentException('You must provide version.');
}

$filesystem = new sfFilesystem();

$version = $argv[1];

echo sprintf("Releasing symfony version \"%s\".\n", $version);

if (is_file('package.xml')) {
    $filesystem->remove(getcwd().DIRECTORY_SEPARATOR.'package.xml');
}

$filesystem->copy(getcwd().'/package.xml.tmpl', getcwd().'/package.xml');

// add class files
$finder = sfFinder::type('file')->relative();
$xml_classes = '';
$dirs = ['lib' => 'php', 'data' => 'data'];
foreach ($dirs as $dir => $role) {
    $class_files = $finder->in($dir);
    foreach ($class_files as $file) {
        $xml_classes .= '<file role="'.$role.'" baseinstalldir="symfony" install-as="'.$file.'" name="'.$dir.'/'.$file.'" />'."\n";
    }
}

// replace tokens
$filesystem->replaceTokens(getcwd().DIRECTORY_SEPARATOR.'package.xml', '##', '##', [
    'SYMFONY_VERSION' => $version,
    'CURRENT_DATE' => date('Y-m-d'),
    'CLASS_FILES' => $xml_classes,
    'STABILITY' => $stability,
]);

list($results) = $filesystem->execute('pear package');
echo $results;

$filesystem->remove(getcwd().DIRECTORY_SEPARATOR.'package.xml');

exit(0);
