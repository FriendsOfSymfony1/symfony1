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
 * sfError404Exception is thrown when a 404 error occurs in an action.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfError404Exception extends \sfException
{
    /**
     * Forwards to the 404 action.
     */
    public function printStackTrace()
    {
        $exception = null === $this->wrappedException ? $this : $this->wrappedException;

        if (\sfConfig::get('sf_debug')) {
            $response = \sfContext::getInstance()->getResponse();
            if (null === $response) {
                $response = new \sfWebResponse(\sfContext::getInstance()->getEventDispatcher());
                \sfContext::getInstance()->setResponse($response);
            }

            $response->setStatusCode(404);

            return parent::printStackTrace();
        }

        // log all exceptions in php log
        if (!\sfConfig::get('sf_test')) {
            error_log($this->getMessage());
        }

        \sfContext::getInstance()->getController()->forward(\sfConfig::get('sf_error_404_module'), \sfConfig::get('sf_error_404_action'));
    }
}
