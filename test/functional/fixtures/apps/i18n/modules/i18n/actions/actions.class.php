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
 * i18n actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class i18nActions extends \sfActions
{
    public function executeIndex()
    {
        $i18n = $this->getContext()->getI18N();

        $this->test = $i18n->__('an english sentence');
        $this->localTest = $i18n->__('a local english sentence');
        $this->otherTest = $i18n->__('an english sentence', [], 'other');
        $this->otherLocalTest = $i18n->__('a local english sentence', [], 'other');
    }

    public function executeIndexForFr()
    {
        // change user culture
        $this->getUser()->setCulture('fr');
        $this->getUser()->setCulture('en');
        $this->getUser()->setCulture('fr');

        $this->forward('i18n', 'index');
    }

    public function executeI18nForm(\sfWebRequest $request)
    {
        $this->form = new \I18nForm();
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('i18n'));
        }
    }

    public function executeI18nCustomCatalogueForm(\sfWebRequest $request)
    {
        $this->form = new \I18nCustomCatalogueForm();
        $this->setTemplate('i18nForm');
    }
}
