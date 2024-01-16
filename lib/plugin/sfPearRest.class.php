<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'PEAR/REST.php';

/**
 * sfPearRest interacts with a PEAR channel.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPearRest extends PEAR_REST
{
    /**
     * @see PEAR_REST::downloadHttp()
     *
     * @param mixed|null $lastmodified
     * @param mixed      $url
     * @param mixed      $accept
     * @param mixed      $channel
     */
    public function downloadHttp($url, $lastmodified = null, $accept = false, $channel = false)
    {
        return parent::downloadHttp($url, $lastmodified, array_merge(false !== $accept ? $accept : [], ["\r\nX-SYMFONY-VERSION: ".SYMFONY_VERSION]));
    }
}
