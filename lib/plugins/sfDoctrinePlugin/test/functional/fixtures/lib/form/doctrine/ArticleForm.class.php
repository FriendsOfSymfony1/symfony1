<?php

/**
 * Article form.
 *
 * @package    form
 * @subpackage Article
 * @version    SVN: $Id$
 */
class ArticleForm extends BaseArticleForm
{
  public function configure()
  {
    $this->embedI18n(array('en', 'fr'));
  }
}