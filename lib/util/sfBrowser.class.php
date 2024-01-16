<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfBrowser simulates a browser which can surf a symfony application.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfBrowser extends sfBrowserBase
{
    protected $listeners = [];
    protected $context;
    protected $currentException;
    protected $rawConfiguration = [];

    /**
     * Returns the current application context.
     *
     * @param bool $forceReload true to force context reload, false otherwise
     *
     * @return sfContext
     */
    public function getContext($forceReload = false)
    {
        if (null === $this->context || $forceReload) {
            $isContextEmpty = null === $this->context;
            $context = $isContextEmpty ? sfContext::getInstance() : $this->context;

            // create configuration
            $currentConfiguration = $context->getConfiguration();
            $configuration = ProjectConfiguration::getApplicationConfiguration($currentConfiguration->getApplication(), $currentConfiguration->getEnvironment(), $currentConfiguration->isDebug());

            // connect listeners
            $configuration->getEventDispatcher()->connect('application.throw_exception', [$this, 'listenToException']);
            foreach ($this->listeners as $name => $listener) {
                $configuration->getEventDispatcher()->connect($name, $listener);
            }

            // create context
            $this->context = sfContext::createInstance($configuration);
            unset($currentConfiguration);

            if (!$isContextEmpty) {
                sfConfig::clear();
                sfConfig::add($this->rawConfiguration);
            } else {
                $this->rawConfiguration = sfConfig::getAll();
            }
        }

        return $this->context;
    }

    public function addListener($name, $listener)
    {
        $this->listeners[$name] = $listener;
    }

    /**
     * Gets response.
     *
     * @return sfWebResponse
     */
    public function getResponse()
    {
        return $this->context->getResponse();
    }

    /**
     * Gets request.
     *
     * @return sfWebRequest
     */
    public function getRequest()
    {
        return $this->context->getRequest();
    }

    /**
     * Gets user.
     *
     * @return sfUser
     */
    public function getUser()
    {
        return $this->context->getUser();
    }

    /**
     * Shutdown function to clean up and remove sessions.
     */
    public function shutdown()
    {
        parent::shutdown();

        // we remove all session data
        sfToolkit::clearDirectory(sfConfig::get('sf_test_cache_dir').'/sessions');
    }

    /**
     * Listener for exceptions.
     *
     * @param sfEvent $event The event to handle
     */
    public function listenToException(sfEvent $event)
    {
        $this->setCurrentException($event->getSubject());
    }

    /**
     * Calls a request to a uri.
     */
    protected function doCall()
    {
        // Before getContext, it can trigger some
        sfConfig::set('sf_test', true);

        // recycle our context object
        $this->context = $this->getContext(true);

        // we register a fake rendering filter
        sfConfig::set('sf_rendering_filter', ['sfFakeRenderingFilter', null]);

        $this->resetCurrentException();

        // dispatch our request
        ob_start();
        $this->context->getController()->dispatch();
        $retval = ob_get_clean();

        // append retval to the response content
        $this->context->getResponse()->setContent($retval);

        // manually shutdown user to save current session data
        if ($this->context->getUser()) {
            $this->context->getUser()->shutdown();
            $this->context->getStorage()->shutdown();
        }
    }
}

class sfFakeRenderingFilter extends sfFilter
{
    public function execute($filterChain)
    {
        $filterChain->execute();

        $this->context->getResponse()->sendContent();
    }
}
