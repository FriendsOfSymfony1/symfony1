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
 * Article form.
 *
 * @version    SVN: $Id$
 */
class ArticleForm extends \BaseArticleForm
{
    public function configure()
    {
        $this->embedI18n(['en', 'fr']);
    }
}
