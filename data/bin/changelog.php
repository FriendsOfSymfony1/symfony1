<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Outputs formatted Subversion log entries.
 *
 * Usage: php data/bin/changelog.php -r12345:67890 /branches/1.3
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
require_once __DIR__.'/../../lib/task/sfFilesystem.class.php';

if (!isset($argv[1])) {
    echo "You must provide a revision range (-r123:456)\n";

    exit(1);
}

if (!isset($argv[2])) {
    echo "You must provide a repository path (/branches/1.4)\n";

    exit(1);
}

$filesystem = new \sfFilesystem();

list($out, $err) = $filesystem->execute('svn info --xml');
$info = new \SimpleXMLElement($out);

list($out, $err) = $filesystem->execute(vsprintf('svn log %s --xml %s', array_map('escapeshellarg', [
    $argv[1],
    (string) $info->entry->repository->root.$argv[2],
])));
$log = new \SimpleXMLElement($out);

foreach ($log->logentry as $logentry) {
    echo sprintf(' * [%d] %s', $logentry['revision'], trim(preg_replace('/\s*\[[\d\., ]+\]\s*/', '', (string) $logentry->msg)));
    echo PHP_EOL;
}
