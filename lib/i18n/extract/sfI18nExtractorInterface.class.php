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
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
interface sfI18nExtractorInterface
{
    /**
     * Extract i18n strings for the given content.
     *
     * @param string $content The content
     *
     * @return array An array of i18n strings
     */
    public function extract($content);
}
