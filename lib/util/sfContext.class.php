<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfContext provides information about the current application context, such as
 * the module and action names and the module directory. References to the
 * main symfony instances are also provided.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 */
class sfContext implements ArrayAccess
{
    /** @var sfEventDispatcher */
    protected $dispatcher;

    /** @var sfApplicationConfiguration */
    protected $configuration;
    protected $mailerConfiguration = [];
    protected $serviceContainerConfiguration = [];
    protected $factories = [];
    protected $hasShutdownUserAndStorage = false;

    protected static $instances = [];
    protected static $current = 'default';

    /**
     * Calls methods defined via sfEventDispatcher.
     *
     * If a method cannot be found via sfEventDispatcher, the method name will
     * be parsed to magically handle getMyFactory() and setMyFactory() methods.
     *
     * @param string $method    The method name
     * @param array  $arguments The method arguments
     *
     * @return mixed The returned value of the called method
     *
     * @throws sfException if call fails
     */
    public function __call($method, $arguments)
    {
        $event = $this->dispatcher->notifyUntil(new sfEvent($this, 'context.method_not_found', ['method' => $method, 'arguments' => $arguments]));
        if (!$event->isProcessed()) {
            $verb = substr($method, 0, 3); // get | set
            $factory = strtolower(substr($method, 3)); // factory name

            if ('get' == $verb && $this->has($factory)) {
                return $this->factories[$factory];
            }
            if ('set' == $verb && isset($arguments[0])) {
                return $this->set($factory, $arguments[0]);
            }

            throw new sfException(sprintf('Call to undefined method %s::%s.', get_class($this), $method));
        }

        return $event->getReturnValue();
    }

    /**
     * Creates a new context instance.
     *
     * @param sfApplicationConfiguration $configuration An sfApplicationConfiguration instance
     * @param string                     $name          A name for this context (application name by default)
     * @param string                     $class         The context class to use (sfContext by default)
     *
     * @return sfContext An sfContext instance
     *
     * @throws sfFactoryException
     */
    public static function createInstance(sfApplicationConfiguration $configuration, $name = null, $class = __CLASS__)
    {
        if (null === $name) {
            $name = $configuration->getApplication();
        }

        self::$current = $name;

        self::$instances[$name] = new $class();

        if (!self::$instances[$name] instanceof sfContext) {
            throw new sfFactoryException(sprintf('Class "%s" is not of the type sfContext.', $class));
        }

        self::$instances[$name]->initialize($configuration);

        return self::$instances[$name];
    }

    /**
     * Initializes the current sfContext instance.
     *
     * @param sfApplicationConfiguration $configuration An sfApplicationConfiguration instance
     */
    public function initialize(sfApplicationConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $configuration->getEventDispatcher();

        try {
            $this->loadFactories();
        } catch (sfException $e) {
            $e->printStackTrace();
        } catch (Exception $e) {
            sfException::createFromException($e)->printStackTrace();
        }

        $this->dispatcher->connect('template.filter_parameters', [$this, 'filterTemplateParameters']);
        $this->dispatcher->connect('response.fastcgi_finish_request', [$this, 'shutdownUserAndStorage']);

        // register our shutdown function
        register_shutdown_function([$this, 'shutdown']);
    }

    /**
     * Retrieves the singleton instance of this class.
     *
     * @param string $name  the name of the sfContext to retrieve
     * @param string $class The context class to use (sfContext by default)
     *
     * @return sfContext an sfContext implementation instance
     *
     * @throws sfException
     */
    public static function getInstance($name = null, $class = __CLASS__)
    {
        if (null === $name) {
            $name = self::$current;
        }

        if (!isset(self::$instances[$name])) {
            throw new sfException(sprintf('The "%s" context does not exist.', $name));
        }

        return self::$instances[$name];
    }

    /**
     * Checks to see if there has been a context created.
     *
     * @param string $name The name of the sfContext to check for
     *
     * @return bool true is instanced, otherwise false
     */
    public static function hasInstance($name = null)
    {
        if (null === $name) {
            $name = self::$current;
        }

        return isset(self::$instances[$name]);
    }

    /**
     * Loads the symfony factories.
     */
    public function loadFactories()
    {
        if (sfConfig::get('sf_use_database')) {
            // setup our database connections
            $this->factories['databaseManager'] = new sfDatabaseManager($this->configuration, ['auto_shutdown' => false]);
        }

        // create a new action stack
        $this->factories['actionStack'] = new sfActionStack();

        if (sfConfig::get('sf_debug') && sfConfig::get('sf_logging_enabled')) {
            $timer = sfTimerManager::getTimer('Factories');
        }

        // include the factories configuration
        require $this->configuration->getConfigCache()->checkConfig('config/factories.yml');

        $this->dispatcher->notify(new sfEvent($this, 'context.load_factories'));

        if (sfConfig::get('sf_debug') && sfConfig::get('sf_logging_enabled')) {
            // @var $timer sfTimer
            $timer->addTime();
        }
    }

    /**
     * Dispatches the current request.
     */
    public function dispatch()
    {
        $this->getController()->dispatch();
    }

    /**
     * Sets the current context to something else.
     *
     * @param string $name The name of the context to switch to
     */
    public static function switchTo($name)
    {
        if (!isset(self::$instances[$name])) {
            $currentConfiguration = sfContext::getInstance()->getConfiguration();
            sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration($name, $currentConfiguration->getEnvironment(), $currentConfiguration->isDebug()));
        }

        self::$current = $name;

        sfContext::getInstance()->getConfiguration()->activate();
    }

    /**
     * Returns the configuration instance.
     *
     * @return sfApplicationConfiguration The current application configuration instance
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Retrieves the current event dispatcher.
     *
     * @return sfEventDispatcher An sfEventDispatcher instance
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Retrieve the action name for this context.
     *
     * @return string|null the currently executing action name if one is set, null otherwise
     */
    public function getActionName()
    {
        // get the last action stack entry
        if ($this->factories['actionStack'] && $lastEntry = $this->factories['actionStack']->getLastEntry()) {
            // @var $lastEntry sfActionStackEntry
            return $lastEntry->getActionName();
        }

        return null;
    }

    /**
     * Retrieve the ActionStack.
     *
     * @return sfActionStack the sfActionStack instance
     */
    public function getActionStack()
    {
        return $this->factories['actionStack'];
    }

    /**
     * Retrieve the controller.
     *
     * @return sfFrontWebController the current sfController implementation instance
     */
    public function getController()
    {
        return isset($this->factories['controller']) ? $this->factories['controller'] : null;
    }

    /**
     * Retrieves the mailer.
     *
     * @return sfMailer the current sfMailer implementation instance
     */
    public function getMailer()
    {
        if (!isset($this->factories['mailer'])) {
            $this->factories['mailer'] = new $this->mailerConfiguration['class']($this->dispatcher, $this->mailerConfiguration);
        }

        return $this->factories['mailer'];
    }

    /**
     * Set mailer configuration.
     *
     * @param array $configuration
     */
    public function setMailerConfiguration($configuration)
    {
        $this->mailerConfiguration = $configuration;
    }

    /**
     * Retrieve the logger.
     *
     * @return sfLogger the current sfLogger implementation instance
     */
    public function getLogger()
    {
        if (!isset($this->factories['logger'])) {
            $this->factories['logger'] = new sfNoLogger($this->dispatcher);
        }

        return $this->factories['logger'];
    }

    /**
     * Retrieve a database connection from the database manager.
     *
     * This is a shortcut to manually getting a connection from an existing
     * database implementation instance.
     *
     * If the [sf_use_database] setting is off, this will return null.
     *
     * @param string $name a database name
     *
     * @return mixed a database instance
     *
     * @throws sfDatabaseException if the requested database name does not exist
     */
    public function getDatabaseConnection($name = 'default')
    {
        if (null !== $this->factories['databaseManager']) {
            return $this->factories['databaseManager']->getDatabase($name)->getConnection();
        }

        return null;
    }

    /**
     * Retrieve the database manager.
     *
     * @return sfDatabaseManager the current sfDatabaseManager instance
     */
    public function getDatabaseManager()
    {
        return isset($this->factories['databaseManager']) ? $this->factories['databaseManager'] : null;
    }

    /**
     * Retrieve the module directory for this context.
     *
     * @return string|null an absolute filesystem path to the directory of the currently executing module if one is set, null otherwise
     */
    public function getModuleDirectory()
    {
        // get the last action stack entry
        if (isset($this->factories['actionStack']) && $lastEntry = $this->factories['actionStack']->getLastEntry()) {
            // @var $lastEntry sfActionStackEntry
            return sfConfig::get('sf_app_module_dir').'/'.$lastEntry->getModuleName();
        }

        return null;
    }

    /**
     * Retrieve the module name for this context.
     *
     * @return string|null the currently executing module name if one is set, null otherwise
     */
    public function getModuleName()
    {
        // get the last action stack entry
        if (isset($this->factories['actionStack']) && $lastEntry = $this->factories['actionStack']->getLastEntry()) {
            // @var $lastEntry sfActionStackEntry
            return $lastEntry->getModuleName();
        }

        return null;
    }

    /**
     * Retrieve the request.
     *
     * @return sfRequest the current sfRequest implementation instance
     */
    public function getRequest()
    {
        return isset($this->factories['request']) ? $this->factories['request'] : null;
    }

    /**
     * Retrieve the response.
     *
     * @return sfResponse the current sfResponse implementation instance
     */
    public function getResponse()
    {
        return isset($this->factories['response']) ? $this->factories['response'] : null;
    }

    /**
     * Set the response object.
     *
     * @param sfResponse $response an sfResponse instance
     */
    public function setResponse($response)
    {
        $this->factories['response'] = $response;
    }

    /**
     * Retrieve the storage.
     *
     * @return sfStorage the current sfStorage implementation instance
     */
    public function getStorage()
    {
        return isset($this->factories['storage']) ? $this->factories['storage'] : null;
    }

    /**
     * Retrieve the view cache manager.
     *
     * @return sfViewCacheManager the current sfViewCacheManager implementation instance
     */
    public function getViewCacheManager()
    {
        return isset($this->factories['viewCacheManager']) ? $this->factories['viewCacheManager'] : null;
    }

    /**
     * Retrieve the i18n instance.
     *
     * @return sfI18N the current sfI18N implementation instance
     *
     * @throws sfConfigurationException
     */
    public function getI18N()
    {
        if (!sfConfig::get('sf_i18n')) {
            throw new sfConfigurationException('You must enable i18n support in your settings.yml configuration file.');
        }

        return $this->factories['i18n'];
    }

    /**
     * Retrieve the routing instance.
     *
     * @return sfRouting the current sfRouting implementation instance
     */
    public function getRouting()
    {
        return isset($this->factories['routing']) ? $this->factories['routing'] : null;
    }

    /**
     * Retrieve the user.
     *
     * @return sfUser the current sfUser implementation instance
     */
    public function getUser()
    {
        return isset($this->factories['user']) ? $this->factories['user'] : null;
    }

    /**
     * Retrieves the service container.
     *
     * @return sfServiceContainer the current sfServiceContainer implementation instance
     */
    public function getServiceContainer()
    {
        if (!isset($this->factories['serviceContainer'])) {
            $this->factories['serviceContainer'] = new $this->serviceContainerConfiguration['class']();
            $this->factories['serviceContainer']->setService('sf_event_dispatcher', $this->configuration->getEventDispatcher());
            $this->factories['serviceContainer']->setService('sf_formatter', new sfFormatter());
            $this->factories['serviceContainer']->setService('sf_user', $this->getUser());
            $this->factories['serviceContainer']->setService('sf_routing', $this->getRouting());
        }

        return $this->factories['serviceContainer'];
    }

    /**
     * Set service ontainer configuration.
     */
    public function setServiceContainerConfiguration(array $config)
    {
        $this->serviceContainerConfiguration = $config;
    }

    /**
     * Retrieves a service from the service container.
     *
     * @param string $id The service identifier
     *
     * @return object The service instance
     */
    public function getService($id)
    {
        return $this->getServiceContainer()->getService($id);
    }

    /**
     * Returns the configuration cache.
     *
     * @return sfConfigCache A sfConfigCache instance
     */
    public function getConfigCache()
    {
        return $this->configuration->getConfigCache();
    }

    /**
     * Returns true if the context object exists (implements the ArrayAccess interface).
     *
     * @param string $name The name of the context object
     *
     * @return bool true if the context object exists, false otherwise
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($name)
    {
        return $this->has($name);
    }

    /**
     * Returns the context object associated with the name (implements the ArrayAccess interface).
     *
     * @param string $name The offset of the value to get
     *
     * @return mixed The context object if exists, null otherwise
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Sets the context object associated with the offset (implements the ArrayAccess interface).
     *
     * @param string $offset Service name
     * @param mixed  $value  Service
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Unsets the context object associated with the offset (implements the ArrayAccess interface).
     *
     * @param string $offset The parameter name
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->factories[$offset]);
    }

    /**
     * Gets an object from the current context.
     *
     * @param string $name The name of the object to retrieve
     *
     * @return object The object associated with the given name
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new sfException(sprintf('The "%s" object does not exist in the current context.', $name));
        }

        return $this->factories[$name];
    }

    /**
     * Puts an object in the current context.
     *
     * @param string $name   The name of the object to store
     * @param mixed  $object The object to store
     */
    public function set($name, $object)
    {
        $this->factories[$name] = $object;
    }

    /**
     * Returns true if an object is currently stored in the current context with the given name, false otherwise.
     *
     * @param string $name The object name
     *
     * @return bool true if the object is not null, false otherwise
     */
    public function has($name)
    {
        return isset($this->factories[$name]);
    }

    /**
     * Listens to the template.filter_parameters event.
     *
     * @param sfEvent $event      An sfEvent instance
     * @param array   $parameters An array of template parameters to filter
     *
     * @return array The filtered parameters array
     */
    public function filterTemplateParameters(sfEvent $event, $parameters)
    {
        $parameters['sf_context'] = $this;
        $parameters['sf_request'] = $this->factories['request'];
        $parameters['sf_params'] = $this->factories['request']->getParameterHolder();
        $parameters['sf_response'] = $this->factories['response'];
        $parameters['sf_user'] = $this->factories['user'];

        return $parameters;
    }

    /**
     * Shuts the user/storage down.
     *
     * @internal Should be called only via invoking "response.fastcgi_finish_request" or context shutting down.
     */
    public function shutdownUserAndStorage()
    {
        if (!$this->hasShutdownUserAndStorage && $this->has('user')) {
            $this->getUser()->shutdown();
            $this->getStorage()->shutdown();

            $this->hasShutdownUserAndStorage = true;
        }
    }

    /**
     * Execute the shutdown procedure.
     */
    public function shutdown()
    {
        $this->shutdownUserAndStorage();

        if ($this->has('routing')) {
            $this->getRouting()->shutdown();
        }

        if (sfConfig::get('sf_use_database')) {
            $this->getDatabaseManager()->shutdown();
        }

        if (sfConfig::get('sf_logging_enabled')) {
            $this->getLogger()->shutdown();
        }
    }
}
