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
 * attachment actions.
 *
 * @author     Your name here
 *
 * @version    SVN: $Id$
 */
class attachmentActions extends \sfActions
{
    /**
     * Executes index action.
     *
     * @param \sfRequest $request A request object
     */
    public function executeIndex(\sfWebRequest $request)
    {
        $this->form = new \AttachmentForm();
        unset($this->form['id']);

        if (
            $request->isMethod('post')
            && $this->form->bindAndSave(
                $request->getParameter($this->form->getName()),
                $request->getFiles($this->form->getName())
            )
        ) {
            return \sfView::SUCCESS;
        }

        return \sfView::INPUT;
    }

    public function executeEditable(\sfWebRequest $request)
    {
        $attachment = \Doctrine_Core::getTable('Attachment')->find($request['id']);
        $this->forward404Unless($attachment, 'Attachment not found');

        $this->form = new \AttachmentForm($attachment);
        if (
            $request->isMethod('post')
            && $this->form->bindAndSave(
                $request->getParameter($this->form->getName()),
                $request->getFiles($this->form->getName())
            )
        ) {
            return \sfView::SUCCESS;
        }

        return \sfView::INPUT;
    }
}
