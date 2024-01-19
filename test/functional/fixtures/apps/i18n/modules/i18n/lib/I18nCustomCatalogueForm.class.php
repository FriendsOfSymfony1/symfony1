<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class I18nCustomCatalogueForm extends \I18nForm
{
    public function configure()
    {
        parent::configure();
        $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('custom');
    }
}
