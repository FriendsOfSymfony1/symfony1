<?php

/**
 * Article form.
 */
class ArticleForm extends BaseArticleForm
{
    public function configure()
    {
        $this->embedI18n(array('en', 'fr'));
    }
}
