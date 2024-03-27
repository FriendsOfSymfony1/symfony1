#!/usr/bin/env php
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
    throw new \InvalidArgumentException('You must specify the version: v1.x.x or next.');
}

$version = $argv[1];

exec('git tag -l', $tags, $resultCode);

if ($resultCode > 0 || count($tags) === 0) {
    throw new \RuntimeException('Reading tag failed.');
}

$latestVersionNumber = $tags[0];
foreach ($tags as $tag) {
    if (version_compare($latestVersionNumber, $tag) < 0) {
        $latestVersionNumber = $tag;
    }
}

if ($version !== 'next') {
    if (!preg_match('/^v1\.([5-9]|\d{2,})\.\d+$/', $version)) {
        throw new \InvalidArgumentException(sprintf('The format of the specified version number is incorrect: "%s"', $version));
    }

    if (in_array($version, $tags)) {
        throw new \InvalidArgumentException(sprintf('The specified version number already exists: "%s"', $version));
    }

    [$latestMajorPart, $latestMinorPart, $latestPatchPart] = explode('.', $latestVersionNumber);
    [$versionMajorPart, $versionMinorPart, $versionPatchPart] = explode('.', $version);

    // This cannot be due to regexp. Just double check.
    if ($latestMajorPart !== $versionMajorPart) {
        throw new \InvalidArgumentException(sprintf('The specified version number can\'t change major: "%s"', $version));
    }

    // changed minor or patch
    if ($latestMinorPart !== $versionMinorPart) {
        if ($versionPatchPart !== '0') {
            throw new \InvalidArgumentException(sprintf('The specified version number should be: "%s.%s.0"', $versionMajorPart, $versionMinorPart));
        }
    } elseif ($latestPatchPart !== $versionPatchPart) {
        $latestPatchPartInt = (int) $latestPatchPart;
        $versionPatchPartInt = (int) $versionPatchPart;

        $nextPatchPartInt = $latestPatchPartInt+1;

        if ($nextPatchPartInt !== $versionPatchPartInt) {
            throw new \InvalidArgumentException(sprintf('Don\'t skip patch version. The specified version number should be: "%s.%s.%d"', $versionMajorPart, $versionMinorPart, $nextPatchPartInt));
        }
    }
} else {
    [$latestMajorPart, $latestMinorPart, $latestPatchPart] = explode('.', $latestVersionNumber);
    $nextPatchPart = (int) $latestPatchPart + 1;
    $version = sprintf('%s.%s.%d', $latestMajorPart, $latestMinorPart, $nextPatchPart);
}

$rawVersion = substr($version, 1);

echo sprintf("Prepare symfony version \"%s\".\n", $rawVersion);

/**
 * prepare sfCoreAutoload class
 */
$file = __DIR__.'/../lib/autoload/sfCoreAutoload.class.php';
$content = file_get_contents($file);

$content = preg_replace('/^define\(.*SYMFONY_VERSION.*$/m', 'define(\'SYMFONY_VERSION\', \''.$rawVersion.'\');', $content, -1, $count);

if ($count !== 1) {
    throw new \RuntimeException('Preparing sfCoreAutoload failed, SYMFONY_VERSION constant not found.');
}

file_put_contents($file, $content);

/**
 * prepare CHANGELOG.md
 */
$file = __DIR__.'/../CHANGELOG.md';
$content = file_get_contents($file);

$nextVersionHeader = <<<EOL
xx/xx/xxxx: Version 1.5.xx
--------------------------




EOL;

$changelogHeader = sprintf('%s: Version %s', date('d/m/Y'), $rawVersion);
$content = preg_replace('/^xx\/xx\/xxxx.*$/m', $nextVersionHeader.$changelogHeader, $content, -1, $count);

if ($count !== 1) {
    throw new \RuntimeException('Preparing CHANGELOG.md failed. Template line not found.');
}

file_put_contents($file, $content);

/**
 * content prepare end
 */

echo "Please check the changes before commit and tagging.\n";

passthru('git diff');

echo "Is everything ok? (y/N)\n";

$answer = readline();

if (strtolower($answer) !== 'y') {
    echo "Revert changes.\n";
    exec('git checkout lib/autoload/sfCoreAutoload.class.php');
    exec('git checkout CHANGELOG.md');
    echo "Stopped.\n";
    exit(1);
}

chdir(__DIR__.'/..');
exec('git add lib/autoload/sfCoreAutoload.class.php');
exec('git add CHANGELOG.md');
exec('git commit -m "Prepare release '.$rawVersion.'"');
exec('git tag -a '.$version.' -m "Release '.$rawVersion.'"');
exec('git push origin '.$version);


exit(0);
