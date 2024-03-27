#!/usr/bin/env php
<?php declare(strict_types=1);

if ($_SERVER['argc'] !== 2) {
    echo sprintf('Usage: %s version', basename(__FILE__), ).PHP_EOL;
    exit(1);
}

$version = $_SERVER['argv'][1];
$rawVersion = str_starts_with($version, 'v') ? substr($version, 1) : $version;

$changelogFilePath = __DIR__.'/../CHANGELOG.md';

if (!is_file($changelogFilePath) || !is_readable($changelogFilePath)) {
    echo 'Changelog file cannot read.'.PHP_EOL;
    exit(1);
}

$buffer = '';
$state = 'not_release_note';

foreach (file($changelogFilePath) as $line) {
    if (str_contains($line, 'Version '.$rawVersion)) {
        $state = 'note_header';
        continue;
    }

    if ($state === 'note_header') {
        $state = 'note';
        continue;
    }

    if ($state === 'note' && str_contains($line, 'Version ')) {
        break;
    }

    if ($state === 'note') {
        $buffer .= $line;
    }
}

$buffer = trim($buffer);

if ($buffer === '') {
    echo 'Release note not found for the specified version.'.PHP_EOL;
    exit(1);
}

echo $buffer.PHP_EOL;
exit(0);
