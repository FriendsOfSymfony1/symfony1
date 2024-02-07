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

$t = new \lime_test(8);

class myConfigHandler extends \sfConfigHandler
{
    public function execute($configFiles)
    {
    }
}

$config = new \myConfigHandler();
$config->initialize();

// ->initialize()
$t->diag('->initialize()');
$config->initialize(['foo' => 'bar']);
$t->is($config->getParameterHolder()->get('foo'), 'bar', '->initialize() takes an array of parameters as its first argument');

// ::replaceConstants()
$t->diag('::replaceConstants()');
\sfConfig::set('foo', 'bar');
$t->is(\sfConfigHandler::replaceConstants('my value with a %foo% constant'), 'my value with a bar constant', '::replaceConstants() replaces constants enclosed in %');

$t->is(\sfConfigHandler::replaceConstants('%Y/%m/%d %H:%M'), '%Y/%m/%d %H:%M', '::replaceConstants() does not replace unknown constants');

\sfConfig::set('foo', 'bar');
$value = [
    'foo' => 'my value with a %foo% constant',
    'bar' => [
        'foo' => 'my value with a %foo% constant',
    ],
];
$value = \sfConfigHandler::replaceConstants($value);
$t->is($value['foo'], 'my value with a bar constant', '::replaceConstants() replaces constants in arrays recursively');
$t->is($value['bar']['foo'], 'my value with a bar constant', '::replaceConstants() replaces constants in arrays recursively');

// ->getParameterHolder()
$t->diag('->getParameterHolder()');
$t->isa_ok($config->getParameterHolder(), 'sfParameterHolder', '->getParameterHolder() returns a parameter holder instance');

// ->replacePath()
$t->diag('->replacePath()');
\sfConfig::set('sf_app_dir', 'ROOTDIR');
$t->is($config->replacePath('test'), 'ROOTDIR/test', '->replacePath() prefix a relative path with "sf_app_dir"');
$t->is($config->replacePath('/test'), '/test', '->replacePath() prefix a relative path with "sf_app_dir"');
