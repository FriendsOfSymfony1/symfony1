<?php

/**
 * Article form.
 *
 * @version    SVN: $Id$
 */
class ArticleForm extends BaseArticleForm
{
    public function configure()
    {
        $this->embedI18n(['en', 'fr']);
    }
}
