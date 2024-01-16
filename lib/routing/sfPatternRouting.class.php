<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPatternRouting class controls the generation and parsing of URLs.
 *
 * It parses and generates URLs by delegating the work to an array of sfRoute objects.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPatternRouting extends sfRouting
{
    /** @var string|null */
    protected $currentRouteName;
    protected $currentInternalUri = [];

    /** @var sfRoute[] */
    protected $routes = [];
    protected $cacheData = [];
    protected $cacheChanged = false;

    /**
     * Initializes this Routing.
     *
     * Available options:
     *
     *  * suffix:                           The default suffix
     *  * variable_prefixes:                An array of characters that starts a variable name (: by default)
     *  * segment_separators:               An array of allowed characters for segment separators (/ and . by default)
     *  * variable_regex:                   A regex that match a valid variable name ([\w\d_]+ by default)
     *  * generate_shortest_url:            Whether to generate the shortest URL possible (true by default)
     *  * extra_parameters_as_query_string: Whether to generate extra parameters as a query string
     *  * lookup_cache_dedicated_keys:      Whether to use dedicated keys for parse/generate cache (false by default)
     *                                      WARNING: When this option is activated, do not use sfFileCache; use a fast access
     *                                      cache backend (like sfAPCCache).
     *
     * @see sfRouting
     *
     * @param mixed $options
     */
    public function initialize(sfEventDispatcher $dispatcher, sfCache $cache = null, $options = [])
    {
        $options = array_merge([
            'variable_prefixes' => [':'],
            'segment_separators' => ['/', '.'],
            'variable_regex' => '[\w\d_]+',
            'load_configuration' => false,
            'suffix' => '',
            'generate_shortest_url' => true,
            'extra_parameters_as_query_string' => true,
            'lookup_cache_dedicated_keys' => false,
        ], $options);

        // for BC
        if ('.' == $options['suffix']) {
            $options['suffix'] = '';
        }

        parent::initialize($dispatcher, $cache, $options);

        if (null !== $this->cache && !$options['lookup_cache_dedicated_keys'] && $cacheData = $this->cache->get('symfony.routing.data')) {
            $this->cacheData = unserialize($cacheData);
        }
    }

    /**
     * @see sfRouting
     */
    public function loadConfiguration()
    {
        if ($this->options['load_configuration'] && $config = $this->getConfigFileName()) {
            include $config;
        }

        parent::loadConfiguration();
    }

    /**
     * @see sfRouting
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function setDefaultParameter($key, $value)
    {
        parent::setDefaultParameter($key, $value);

        foreach ($this->routes as $route) {
            if (is_object($route)) {
                $route->setDefaultParameters($this->defaultParameters);
            }
        }
    }

    /**
     * @see sfRouting
     *
     * @param mixed $parameters
     */
    public function setDefaultParameters($parameters)
    {
        parent::setDefaultParameters($parameters);

        foreach ($this->routes as $route) {
            if (is_object($route)) {
                $route->setDefaultParameters($this->defaultParameters);
            }
        }
    }

    /**
     * @see sfRouting
     *
     * @param mixed $withRouteName
     */
    public function getCurrentInternalUri($withRouteName = false)
    {
        return null === $this->currentRouteName ? null : $this->currentInternalUri[$withRouteName ? 0 : 1];
    }

    /**
     * Gets the current route name.
     *
     * @return string The route name
     */
    public function getCurrentRouteName()
    {
        return $this->currentRouteName;
    }

    /**
     * @see sfRouting
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @see  sfRouting
     *
     * @param mixed $name
     */
    public function getRoute($name)
    {
        if (!array_key_exists($name, $this->routes)) {
            throw new sfException(sprintf('Route "%s" is not defined.', $name));
        }

        $route = $this->routes[$name];

        if (is_string($route)) {
            $this->routes[$name] = $route = unserialize($route);
            $route->setDefaultParameters($this->defaultParameters);
        }

        return $route;
    }

    /**
     * @see sfRouting
     *
     * @param mixed $routes
     */
    public function setRoutes($routes)
    {
        foreach ($routes as $name => $route) {
            $this->connect($name, $route);
        }
    }

    /**
     * @see sfRouting
     */
    public function hasRoutes()
    {
        return count($this->routes) ? true : false;
    }

    /**
     * @see sfRouting
     */
    public function clearRoutes()
    {
        if ($this->options['logging']) {
            $this->dispatcher->notify(new sfEvent($this, 'application.log', ['Clear all current routes']));
        }

        $this->routes = [];
    }

    /**
     * Returns true if the route name given is defined.
     *
     * @param string $name The route name
     *
     * @return bool
     */
    public function hasRouteName($name)
    {
        return array_key_exists($name, $this->routes);
    }

    /**
     * Adds a new route at the beginning of the current list of routes.
     *
     * @see connect
     *
     * @param string  $name
     * @param sfRoute $route
     */
    public function prependRoute($name, $route)
    {
        $routes = $this->routes;
        $this->routes = [];
        $this->connect($name, $route);
        $this->routes = array_merge($this->routes, $routes);
    }

    /**
     * Adds a new route.
     *
     * Alias for the connect method.
     *
     * @see connect
     *
     * @param string  $name
     * @param sfRoute $route
     *
     * @return array
     */
    public function appendRoute($name, $route)
    {
        return $this->connect($name, $route);
    }

    /**
     * Adds a new route before a given one in the current list of routes.
     *
     * @see connect
     *
     * @param string  $pivot
     * @param string  $name
     * @param sfRoute $route
     *
     * @throws sfConfigurationException
     */
    public function insertRouteBefore($pivot, $name, $route)
    {
        if (!isset($this->routes[$pivot])) {
            throw new sfConfigurationException(sprintf('Unable to insert route "%s" before inexistent route "%s".', $name, $pivot));
        }

        $routes = $this->routes;
        $this->routes = [];
        $newroutes = [];
        foreach ($routes as $key => $value) {
            if ($key == $pivot) {
                $this->connect($name, $route);
                $newroutes = array_merge($newroutes, $this->routes);
            }
            $newroutes[$key] = $value;
        }

        $this->routes = $newroutes;
    }

    /**
     * Adds a new route at the end of the current list of routes.
     *
     * A route string is a string with 2 special constructions:
     * - :string: :string denotes a named parameter (available later as $request->getParameter('string'))
     * - *: * match an indefinite number of parameters in a route
     *
     * Here is a very common rule in a symfony project:
     *
     * <code>
     * $r->connect('default', new sfRoute('/:module/:action/*'));
     * </code>
     *
     * @param string  $name  The route name
     * @param sfRoute $route A sfRoute instance
     *
     * @return array current routes
     */
    public function connect($name, $route)
    {
        $routes = $route instanceof sfRouteCollection ? $route : [$name => $route];
        foreach (self::flattenRoutes($routes) as $name => $route) {
            $this->routes[$name] = $route;
            $this->configureRoute($route);

            if ($this->options['logging']) {
                $this->dispatcher->notify(new sfEvent($this, 'application.log', [sprintf('Connect %s "%s" (%s)', get_class($route), $name, $route->getPattern())]));
            }
        }
    }

    public function configureRoute(sfRoute $route)
    {
        $route->setDefaultParameters($this->defaultParameters);
        $route->setDefaultOptions($this->options);
    }

    /**
     * @see sfRouting
     *
     * @param mixed $name
     * @param mixed $params
     * @param mixed $absolute
     */
    public function generate($name, $params = [], $absolute = false)
    {
        // fetch from cache
        if (null !== $this->cache) {
            $cacheKey = $this->getGenerateCacheKey($name, (array) $params);
            if ($this->options['lookup_cache_dedicated_keys'] && $url = $this->cache->get($cacheKey)) {
                return $this->fixGeneratedUrl($url, $absolute);
            }
            if (isset($this->cacheData[$cacheKey])) {
                return $this->fixGeneratedUrl($this->cacheData[$cacheKey], $absolute);
            }
        }

        if ($name) {
            $route = $this->getRoute($name);
        } else {
            // find a matching route
            if (false === $route = $this->getRouteThatMatchesParameters($params)) {
                throw new sfConfigurationException(sprintf('Unable to find a matching route to generate url for params "%s".', is_object($params) ? 'Object('.get_class($params).')' : str_replace("\n", '', var_export($params, true))));
            }
        }

        $url = $route->generate($params, $this->options['context'], $absolute);

        // store in cache
        if (null !== $this->cache) {
            if ($this->options['lookup_cache_dedicated_keys']) {
                $this->cache->set($cacheKey, $url);
            } else {
                $this->cacheChanged = true;
                $this->cacheData[$cacheKey] = $url;
            }
        }

        return $this->fixGeneratedUrl($url, $absolute);
    }

    /**
     * @see sfRouting
     *
     * @param mixed $url
     */
    public function parse($url)
    {
        if (false === $info = $this->findRoute($url)) {
            $this->currentRouteName = null;
            $this->currentInternalUri = [];

            return false;
        }

        if ($this->options['logging']) {
            $this->dispatcher->notify(new sfEvent($this, 'application.log', [sprintf('Match route "%s" (%s) for %s with parameters %s', $info['name'], $info['pattern'], $url, str_replace("\n", '', var_export($info['parameters'], true)))]));
        }

        // store the current internal URI
        $this->updateCurrentInternalUri($info['name'], $info['parameters']);

        $route = $this->getRoute($info['name']);

        $route->bind($this->options['context'], $info['parameters']);
        $info['parameters']['_sf_route'] = $route;

        return $info['parameters'];
    }

    /**
     * Finds a matching route for given URL.
     *
     * Returns false if no route matches.
     *
     * Returned array contains:
     *
     *  - name:       name or alias of the route that matched
     *  - pattern:    the compiled pattern of the route that matched
     *  - parameters: array containing key value pairs of the request parameters including defaults
     *
     * @param string $url URL to be parsed
     *
     * @return array|false An array with routing information or false if no route matched
     */
    public function findRoute($url)
    {
        $url = $this->normalizeUrl($url);

        // fetch from cache
        if (null !== $this->cache) {
            $cacheKey = $this->getParseCacheKey($url);
            if ($this->options['lookup_cache_dedicated_keys'] && $info = $this->cache->get($cacheKey)) {
                return unserialize($info);
            }
            if (isset($this->cacheData[$cacheKey])) {
                return $this->cacheData[$cacheKey];
            }
        }

        $info = $this->getRouteThatMatchesUrl($url);

        // store in cache
        if (null !== $this->cache) {
            if ($this->options['lookup_cache_dedicated_keys']) {
                $this->cache->set($cacheKey, serialize($info));
            } else {
                $this->cacheChanged = true;
                $this->cacheData[$cacheKey] = $info;
            }
        }

        return $info;
    }

    public static function flattenRoutes($routes)
    {
        $flattenRoutes = [];
        foreach ($routes as $name => $route) {
            if ($route instanceof sfRouteCollection) {
                $flattenRoutes = array_merge($flattenRoutes, self::flattenRoutes($route));
            } else {
                $flattenRoutes[$name] = $route;
            }
        }

        return $flattenRoutes;
    }

    /**
     * @see sfRouting
     */
    public function shutdown()
    {
        if (null !== $this->cache && $this->cacheChanged) {
            $this->cacheChanged = false;
            $this->cache->set('symfony.routing.data', serialize($this->cacheData));
        }
    }

    protected function getConfigFileName()
    {
        return sfContext::getInstance()->getConfigCache()->checkConfig('config/routing.yml', true);
    }

    protected function getGenerateCacheKey($name, $params)
    {
        return 'generate_'.$name.'_'.md5(serialize(array_merge($this->defaultParameters, $params))).'_'.md5(serialize($this->options['context']));
    }

    protected function updateCurrentInternalUri($name, array $parameters)
    {
        // store the route name
        $this->currentRouteName = $name;

        $internalUri = ['@'.$this->currentRouteName, $parameters['module'].'/'.$parameters['action']];
        unset($parameters['module'], $parameters['action']);

        $params = [];
        foreach ($parameters as $key => $value) {
            $params[] = $key.'='.$value;
        }

        // sort to guaranty unicity
        sort($params);

        $params = $params ? '?'.implode('&', $params) : '';

        $this->currentInternalUri = [$internalUri[0].$params, $internalUri[1].$params];
    }

    protected function getParseCacheKey($url)
    {
        return 'parse_'.$url.'_'.md5(serialize($this->options['context']));
    }

    protected function getRouteThatMatchesUrl($url)
    {
        foreach ($this->routes as $name => $route) {
            $route = $this->getRoute($name);

            if (false === $parameters = $route->matchesUrl($url, $this->options['context'])) {
                continue;
            }

            return ['name' => $name, 'pattern' => $route->getPattern(), 'parameters' => $parameters];
        }

        return false;
    }

    protected function getRouteThatMatchesParameters($parameters)
    {
        foreach ($this->routes as $name => $route) {
            $route = $this->getRoute($name);

            if ($route->matchesParameters($parameters, $this->options['context'])) {
                return $route;
            }
        }

        return false;
    }

    protected function normalizeUrl($url)
    {
        // an URL should start with a '/', mod_rewrite doesn't respect that, but no-mod_rewrite version does.
        if ('/' != substr($url, 0, 1)) {
            $url = '/'.$url;
        }

        // we remove the query string
        if (false !== $pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }

        // remove multiple /
        return preg_replace('#/+#', '/', $url);
    }
}
