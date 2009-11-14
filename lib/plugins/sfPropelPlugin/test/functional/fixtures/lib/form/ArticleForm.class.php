<?php

/**
 * Article form.
 *
 * @package    form
 * @subpackage article
 * @version    SVN: $Id$
 */
class ArticleForm extends BaseArticleForm
{
  public function configure()
  {
    if ($category = $this->getObject()->getCategory())
    {
      $this->embedForm('category', new CategoryForm($this->getObject()->getCategory()));
    }

    if ($this->getOption('with_attachment'))
    {
      $attachment = new Attachment();
      $attachment->setArticle($this->object);

      $attachmentForm = new AttachmentForm($attachment);
      unset($attachmentForm['article_id']);

      $this->embedForm('attachment', $attachmentForm);
    }
  }
}
