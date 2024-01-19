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
 * sfLoggerInterface is the interface all symfony loggers must implement.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
interface sfLoggerInterface
{
    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     */
    public function log($message, $priority = null);
}
