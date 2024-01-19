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
 * sfLoggerWrapper wraps a class that implements sfLoggerInterface into a real sfLogger object.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfLoggerWrapper extends \sfLogger
{
    /** @var \sfLoggerInterface */
    protected $logger;

    /**
     * Creates a new logger wrapper.
     *
     * @param \sfLoggerInterface $logger The wrapped logger
     */
    public function __construct(\sfLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs a message.
     *
     * @param string $message  Message
     * @param int    $priority Message priority
     */
    protected function doLog($message, $priority)
    {
        $this->logger->log($message, $priority);
    }
}
