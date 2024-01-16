<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'PEAR/Downloader.php';

/**
 * sfPearDownloader downloads files from the Internet.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPearDownloader extends PEAR_Downloader
{
    /**
     * @see PEAR_REST::downloadHttp()
     *
     * @param mixed|null $callback
     * @param mixed|null $lastmodified
     * @param mixed      $url
     * @param mixed      $ui
     * @param mixed      $save_dir
     * @param mixed      $accept
     * @param mixed      $channel
     */
    public function downloadHttp($url, &$ui, $save_dir = '.', $callback = null, $lastmodified = null, $accept = false, $channel = false)
    {
        return parent::downloadHttp($url, $ui, $save_dir, $callback, $lastmodified, $accept, $channel);
    }
}
