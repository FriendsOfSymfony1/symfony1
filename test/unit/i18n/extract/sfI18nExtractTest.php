<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../bootstrap/unit.php';

$t = new \lime_test(3);

class ProjectConfiguration extends \sfProjectConfiguration
{
}

class TestConfiguration extends \sfApplicationConfiguration
{
    public function getI18NGlobalDirs()
    {
        return [__DIR__.'/../fixtures'];
    }
}

$configuration = new \TestConfiguration('test', true, \sfConfig::get('sf_test_cache_dir', sys_get_temp_dir()));
$cache = new \sfNoCache();
$i18n = new \sfI18N($configuration, $cache);

/**
 * @internal
 *
 * @coversNothing
 */
class sfI18nExtractTest extends \sfI18nExtract
{
    public function extract()
    {
        $this->updateMessages($this->getMessages());
    }

    public function getMessages()
    {
        return ['toto', 'an english sentence'];
    }
}

// ->initialize()
$t->diag('->initialize()');
$extract = new \sfI18nExtractTest($i18n, 'fr');
$t->is(count($extract->getCurrentMessages()), 4, '->initialize() initializes the current i18n messages');
$extract->extract();

// ->getOldMessages()
$t->diag('->getOldMessages()');
$t->is($extract->getOldMessages(), array_diff($extract->getCurrentMessages(), $extract->getMessages()), '->getOldMessages() returns old messages');

// ->getNewMessages()
$t->diag('->getNewMessages()');
$t->is($extract->getNewMessages(), array_diff($extract->getMessages(), $extract->getCurrentMessages()), '->getNewMessages() returns new messages');
