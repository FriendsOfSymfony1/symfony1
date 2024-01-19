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

$t = new \lime_test(7);

class sfMessageSource_Simple extends \sfMessageSource_File
{
    protected $dataExt = '.xml';

    public function delete($message, $catalogue = 'messages')
    {
    }

    public function update($text, $target, $comments, $catalogue = 'messages')
    {
    }

    public function save($catalogue = 'messages')
    {
    }

    public function getCatalogueByDir($catalogue)
    {
        return parent::getCatalogueByDir($catalogue);
    }
}

$source = \sfMessageSource::factory('Simple', __DIR__.'/fixtures');
$source->setCulture('fr_FR');

// ->getCatalogueByDir()
$t->diag('->getCatalogueByDir()');
$t->is($source->getCatalogueByDir('messages'), ['fr_FR/messages.xml', 'fr/messages.xml'], '->getCatalogueByDir() returns catalogues by directory');

// ->getCatalogueList()
$t->diag('->getCatalogueList()');
$t->is($source->getCatalogueList('messages'), ['fr_FR/messages.xml', 'fr/messages.xml', 'messages.fr_FR.xml', 'messages.fr.xml', 'messages.xml'], '->getCatalogueByDir() returns all catalogues for the current culture');

// ->getSource()
$t->diag('->getSource()');
$t->is($source->getSource('fr_FR/messages.xml'), __DIR__.'/fixtures/fr_FR/messages.xml', '->getSource() returns the full path name to a specific variant');

// ->isValidSource()
$t->diag('->isValidSource()');
$t->is($source->isValidSource($source->getSource('fr_FR/messages.xml')), false, '->isValidSource() returns false if the source is not valid');
$t->is($source->isValidSource($source->getSource('messages.fr.xml')), true, '->isValidSource() returns true if the source is valid');

// ->getLastModified()
$t->diag('->getLastModified()');
$t->is($source->getLastModified($source->getSource('fr_FR/messages.xml')), 0, '->getLastModified() returns 0 if the source does not exist');
$t->is($source->getLastModified($source->getSource('messages.fr.xml')), filemtime($source->getSource('messages.fr.xml')), '->getLastModified() returns the last modified time of the source');
