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

exit(0);
