<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLoggerWrapper wraps a class that implements sfLoggerInterface into a real sfLogger object.
 *
 * @package    symfony
 * @subpackage log
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfLoggerWrapper extends sfLogger
{
  /** @var sfLoggerInterface */
  protected
    $logger = null;

  /**
   * Creates a new logger wrapper
   *
   * @param sfLoggerInterface $logger The wrapped logger
   */
  public function __construct(sfLoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param int    $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    $this->logger->log($message, $priority);
  }
}
