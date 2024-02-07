<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function log_message($message, $priority = 'info')
{
    if (\sfConfig::get('sf_logging_enabled')) {
        \sfContext::getInstance()->getEventDispatcher()->notify(new \sfEvent(null, 'application.log', [$message, 'priority' => constant('sfLogger::'.strtoupper($priority))]));
    }
}
