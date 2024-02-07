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
 * sfActions executes all the logic for the current request.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 *
 * @version    SVN: $Id$
 */
abstract class sfActions extends \sfAction
{
    /**
     * Dispatches to the action defined by the 'action' parameter of the sfRequest object.
     *
     * This method try to execute the executeXXX() method of the current object where XXX is the
     * defined action name.
     *
     * @param \sfRequest $request The current sfRequest object
     *
     * @return string A string containing the view name associated with this action
     *
     * @throws \sfInitializationException
     *
     * @see \sfAction
     */
    public function execute($request)
    {
        // dispatch action
        $actionToRun = 'execute'.ucfirst($this->getActionName());

        if ('execute' === $actionToRun) {
            // no action given
            throw new \sfInitializationException(sprintf('sfAction initialization failed for module "%s". There was no action given.', $this->getModuleName()));
        }

        if (!is_callable([$this, $actionToRun])) {
            // action not found
            throw new \sfInitializationException(sprintf('sfAction initialization failed for module "%s", action "%s". You must create a "%s" method.', $this->getModuleName(), $this->getActionName(), $actionToRun));
        }

        if (\sfConfig::get('sf_logging_enabled')) {
            $this->dispatcher->notify(new \sfEvent($this, 'application.log', [sprintf('Call "%s->%s()"', get_class($this), $actionToRun)]));
        }

        // run action
        return $this->{$actionToRun}($request);
    }
}
