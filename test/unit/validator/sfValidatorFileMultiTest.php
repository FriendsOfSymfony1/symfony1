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

$t = new \lime_test(9);

$tmpDir = sys_get_temp_dir();
$content = 'This is an ASCII file.';
$content2 = 'This is an ASCII file. And another one.';
file_put_contents($tmpDir.'/test.txt', $content);
file_put_contents($tmpDir.'/test2.txt', $content2);

// ->clean()
$t->diag('->clean()');
$v = new \sfValidatorFileMulti();

$f = $v->clean([
    ['tmp_name' => $tmpDir.'/test.txt'],
    ['tmp_name' => $tmpDir.'/test2.txt'],
]);

$t->ok(is_array($f), '->clean() returns an array of sfValidatedFile instances');

$t->ok($f[0] instanceof \sfValidatedFile, '->clean() returns an array of sfValidatedFile');
$t->is($f[0]->getOriginalName(), '', '->clean() returns an array of sfValidatedFile with an empty original name if the name is not passed in the initial value');
$t->is($f[0]->getSize(), strlen($content), '->clean() returns an array of sfValidatedFile with a computed file size if the size is not passed in the initial value');
$t->is($f[0]->getType(), 'text/plain', '->clean() returns an array of sfValidatedFile with a guessed content type');

$t->ok($f[1] instanceof \sfValidatedFile, '->clean() returns an array of sfValidatedFile');
$t->is($f[1]->getOriginalName(), '', '->clean() returns an array of sfValidatedFile with an empty original name if the name is not passed in the initial value');
$t->is($f[1]->getSize(), strlen($content2), '->clean() returns an array of sfValidatedFile with a computed file size if the size is not passed in the initial value');
$t->is($f[1]->getType(), 'text/plain', '->clean() returns an array of sfValidatedFile with a guessed content type');

unlink($tmpDir.'/test.txt');
unlink($tmpDir.'/test2.txt');
\sfToolkit::clearDirectory($tmpDir.'/foo');
