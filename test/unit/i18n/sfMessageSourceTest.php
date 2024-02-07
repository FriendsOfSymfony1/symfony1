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

$t = new \lime_test(3);

class sfMessageSource_Simple extends \sfMessageSource
{
    public function __construct($source)
    {
    }

    public function delete($message, $catalogue = 'messages')
    {
    }

    public function update($text, $target, $comments, $catalogue = 'messages')
    {
    }

    public function catalogues()
    {
    }

    public function save($catalogue = 'messages')
    {
    }

    public function getId()
    {
    }
}

// ::factory()
$t->diag('::factory()');
$source = \sfMessageSource::factory('Simple');
$t->ok($source instanceof \sfIMessageSource, '::factory() returns a sfMessageSource instance');

// ->getCulture() ->setCulture()
$t->diag('->getCulture() ->setCulture()');
$source->setCulture('en');
$t->is($source->getCulture(), 'en', '->setCulture() changes the source culture');
$source->setCulture('fr');
$t->is($source->getCulture(), 'fr', '->getCulture() gets the current source culture');
