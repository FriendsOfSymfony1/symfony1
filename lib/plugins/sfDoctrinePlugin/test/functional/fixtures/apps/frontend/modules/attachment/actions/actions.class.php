<?php

/**
 * attachment actions.
 *
 * @package    symfony12
 * @subpackage attachment
 * @author     Your name here
 * @version    SVN: $Id$
 */
class attachmentActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new AttachmentForm();
    unset($this->form['id']);

    if (
      $request->isMethod('post')
      &&
      $this->form->bindAndSave(
        $request->getParameter($this->form->getName()),
        $request->getFiles($this->form->getName())
      )
    )
    {
      return sfView::SUCCESS;
    }

    return sfView::INPUT;
  }

  public function executeEditable(sfWebRequest $request)
  {
    $attachment = Doctrine_Core::getTable('Attachment')->find($request['id']);
    $this->forward404Unless($attachment, 'Attachment not found');

    $this->form = new AttachmentForm($attachment);
    if (
      $request->isMethod('post')
      &&
      $this->form->bindAndSave(
        $request->getParameter($this->form->getName()),
        $request->getFiles($this->form->getName())
      )
    )
    {
      return sfView::SUCCESS;
    }

    return sfView::INPUT;
  }
}
