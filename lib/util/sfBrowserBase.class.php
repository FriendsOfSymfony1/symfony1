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
 * sfBrowserBase is the base class for sfBrowser.
 *
 * It implements features that is independent from the symfony controllers.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfBrowserBase
{
    protected $hostname;
    protected $remote;
    protected $dom;
    protected $stack = [];
    protected $stackPosition = -1;
    protected $cookieJar = [];
    protected $fields = [];
    protected $files = [];
    protected $vars = [];
    protected $defaultServerArray = [];
    protected $headers = [];
    protected $currentException;
    protected $domCssSelector;

    /**
     * Class constructor.
     *
     * @param string $hostname Hostname to browse
     * @param string $remote   Remote address to spook
     * @param array  $options  Options for sfBrowser
     */
    public function __construct($hostname = null, $remote = null, $options = [])
    {
        $this->initialize($hostname, $remote, $options);
    }

    /**
     * Initializes sfBrowser - sets up environment.
     *
     * @param string $hostname Hostname to browse
     * @param string $remote   Remote address to spook
     * @param array  $options  Options for sfBrowser
     */
    public function initialize($hostname = null, $remote = null, $options = [])
    {
        unset($_SERVER['argv'], $_SERVER['argc']);

        // setup our fake environment
        $this->hostname = null === $hostname ? 'localhost' : $hostname;
        $this->remote = null === $remote ? '127.0.0.1' : $remote;

        // we set a session id (fake cookie / persistence)
        $this->newSession();

        // store default global $_SERVER array
        $this->defaultServerArray = $_SERVER;

        // register our shutdown function
        register_shutdown_function([$this, 'shutdown']);
    }

    /**
     * Sets variable name.
     *
     * @param string $name  The variable name
     * @param mixed  $value The value
     *
     * @return \sfBrowserBase
     */
    public function setVar($name, $value)
    {
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * Sets a HTTP header for the very next request.
     *
     * @param string $header The header name
     * @param string $value  The header value
     */
    public function setHttpHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Sets a cookie.
     *
     * @param string $name     The cookie name
     * @param string $value    Value for the cookie
     * @param string $expire   Cookie expiration period
     * @param string $path     Path
     * @param string $domain   Domain name
     * @param bool   $secure   If secure
     * @param bool   $httpOnly If uses only HTTP
     *
     * @return \sfBrowserBase This sfBrowserBase instance
     */
    public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false)
    {
        $this->cookieJar[$name] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => (bool) $secure,
            'httpOnly' => $httpOnly,
        ];

        return $this;
    }

    /**
     * Removes a cookie by name.
     *
     * @param string $name The cookie name
     *
     * @return \sfBrowserBase This sfBrowserBase instance
     */
    public function removeCookie($name)
    {
        unset($this->cookieJar[$name]);

        return $this;
    }

    /**
     * Clears all cookies.
     *
     * @return \sfBrowserBase This sfBrowserBase instance
     */
    public function clearCookies()
    {
        $this->cookieJar = [];

        return $this;
    }

    /**
     * Sets username and password for simulating http authentication.
     *
     * @param string $username The username
     * @param string $password The password
     *
     * @return \sfBrowserBase
     */
    public function setAuth($username, $password)
    {
        $this->vars['PHP_AUTH_USER'] = $username;
        $this->vars['PHP_AUTH_PW'] = $password;

        return $this;
    }

    /**
     * Gets a uri.
     *
     * @param string $uri         The URI to fetch
     * @param array  $parameters  The Request parameters
     * @param bool   $changeStack Change the browser history stack?
     *
     * @return \sfBrowserBase
     */
    public function get($uri, $parameters = [], $changeStack = true)
    {
        return $this->call($uri, 'get', $parameters, $changeStack);
    }

    /**
     * Posts a uri.
     *
     * @param string $uri         The URI to fetch
     * @param array  $parameters  The Request parameters
     * @param bool   $changeStack Change the browser history stack?
     *
     * @return \sfBrowserBase
     */
    public function post($uri, $parameters = [], $changeStack = true)
    {
        return $this->call($uri, 'post', $parameters, $changeStack);
    }

    /**
     * Calls a request to a uri.
     *
     * @param string $uri         The URI to fetch
     * @param string $method      The request method
     * @param array  $parameters  The Request parameters
     * @param bool   $changeStack Change the browser history stack?
     *
     * @return \sfBrowserBase
     */
    public function call($uri, $method = 'get', $parameters = [], $changeStack = true)
    {
        // check that the previous call() hasn't returned an uncatched exception
        $this->checkCurrentExceptionIsEmpty();

        $uri = $this->fixUri($uri);

        // add uri to the stack
        if ($changeStack) {
            $this->stack = array_slice($this->stack, 0, $this->stackPosition + 1);
            $this->stack[] = [
                'uri' => $uri,
                'method' => $method,
                'parameters' => $parameters,
            ];
            $this->stackPosition = count($this->stack) - 1;
        }

        list($path, $queryString) = false !== ($pos = strpos($uri, '?')) ? [substr($uri, 0, $pos), substr($uri, $pos + 1)] : [$uri, ''];
        $queryString = html_entity_decode($queryString);

        // remove anchor
        $path = preg_replace('/#.*/', '', $path);

        // removes all fields from previous request
        $this->fields = [];

        // prepare the request object
        $_SERVER = $this->defaultServerArray;
        $_SERVER['HTTP_HOST'] = $this->hostname;
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['HTTP_USER_AGENT'] = 'PHP5/CLI';
        $_SERVER['REMOTE_ADDR'] = $this->remote;
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['PATH_INFO'] = $path;
        $_SERVER['REQUEST_URI'] = '/index.php'.$uri;
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = '/index.php';
        $_SERVER['QUERY_STRING'] = $queryString;

        if ($this->stackPosition >= 1) {
            $_SERVER['HTTP_REFERER'] = sprintf('http%s://%s%s', isset($this->defaultServerArray['HTTPS']) ? 's' : '', $this->hostname, $this->stack[$this->stackPosition - 1]['uri']);
        }

        foreach ($this->vars as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }

        foreach ($this->headers as $header => $value) {
            $_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $header))] = $value;
        }
        $this->headers = [];

        // request parameters
        $_GET = $_POST = [];
        if (in_array(strtoupper($method), ['POST', 'DELETE', 'PUT', 'PATCH'])) {
            if (isset($parameters['_with_csrf']) && $parameters['_with_csrf']) {
                unset($parameters['_with_csrf']);
                $form = new \BaseForm();
                $parameters[$form->getCSRFFieldName()] = $form->getCSRFToken();
            }

            $_POST = $parameters;
        }
        if ('GET' == strtoupper($method)) {
            $_GET = $parameters;
        }

        // handle input type="file" fields
        $_FILES = [];
        if (count($this->files)) {
            $_FILES = $this->files;
        }
        $this->files = [];

        parse_str($queryString, $qs);
        if (is_array($qs)) {
            $_GET = array_merge($qs, $_GET);
        }

        // expire cookies
        $cookies = $this->cookieJar;
        foreach ($cookies as $name => $cookie) {
            if ($cookie['expire'] && $cookie['expire'] < time()) {
                unset($this->cookieJar[$name]);
            }
        }

        // restore cookies
        $_COOKIE = [];
        foreach ($this->cookieJar as $name => $cookie) {
            $_COOKIE[$name] = $cookie['value'];
        }

        $this->doCall();

        $response = $this->getResponse();

        // save cookies
        foreach ($response->getCookies() as $name => $cookie) {
            // FIXME: deal with path, secure, ...
            $this->cookieJar[$name] = $cookie;
        }

        // support for the ETag header
        if ($etag = $response->getHttpHeader('Etag')) {
            $this->vars['HTTP_IF_NONE_MATCH'] = $etag;
        } else {
            unset($this->vars['HTTP_IF_NONE_MATCH']);
        }

        // support for the last modified header
        if ($lastModified = $response->getHttpHeader('Last-Modified')) {
            $this->vars['HTTP_IF_MODIFIED_SINCE'] = $lastModified;
        } else {
            unset($this->vars['HTTP_IF_MODIFIED_SINCE']);
        }

        // for HTML/XML content, create a DOM and sfDomCssSelector objects for the response content
        $this->dom = null;
        $this->domCssSelector = null;
        if (preg_match('/(x|ht)ml/i', $response->getContentType(), $matches)) {
            $this->dom = new \DOMDocument('1.0', $response->getCharset());
            $this->dom->validateOnParse = true;
            if ('x' == $matches[1]) {
                @$this->dom->loadXML($response->getContent());
            } else {
                if ($content = $response->getContent()) {
                    @$this->dom->loadHTML($content);
                }
            }
            $this->domCssSelector = new \sfDomCssSelector($this->dom);
        }

        return $this;
    }

    /**
     * Go back in the browser history stack.
     *
     * @return \sfBrowserBase
     */
    public function back()
    {
        if ($this->stackPosition < 1) {
            throw new \LogicException('You are already on the first page.');
        }

        --$this->stackPosition;

        return $this->call($this->stack[$this->stackPosition]['uri'], $this->stack[$this->stackPosition]['method'], $this->stack[$this->stackPosition]['parameters'], false);
    }

    /**
     * Go forward in the browser history stack.
     *
     * @return \sfBrowserBase
     */
    public function forward()
    {
        if ($this->stackPosition > count($this->stack) - 2) {
            throw new \LogicException('You are already on the last page.');
        }

        ++$this->stackPosition;

        return $this->call($this->stack[$this->stackPosition]['uri'], $this->stack[$this->stackPosition]['method'], $this->stack[$this->stackPosition]['parameters'], false);
    }

    /**
     * Reload the current browser.
     *
     * @return \sfBrowserBase
     */
    public function reload()
    {
        if (-1 == $this->stackPosition) {
            throw new \LogicException('No page to reload.');
        }

        return $this->call($this->stack[$this->stackPosition]['uri'], $this->stack[$this->stackPosition]['method'], $this->stack[$this->stackPosition]['parameters'], false);
    }

    /**
     * Get response DOM CSS selector.
     *
     * @return \sfDomCssSelector
     */
    public function getResponseDomCssSelector()
    {
        if (null === $this->domCssSelector) {
            throw new \LogicException('The DOM is not accessible because the browser response content type is not HTML.');
        }

        return $this->domCssSelector;
    }

    /**
     * Get the response DOM XPath selector.
     *
     * @return \DOMXPath
     *
     * @uses getResponseDom()
     */
    public function getResponseDomXpath()
    {
        return new \DOMXPath($this->getResponseDom());
    }

    /**
     * Get response DOM.
     *
     * @return \sfDomCssSelector
     */
    public function getResponseDom()
    {
        if (null === $this->dom) {
            throw new \LogicException('The DOM is not accessible because the browser response content type is not HTML.');
        }

        return $this->dom;
    }

    /**
     * Gets response.
     *
     * @return \sfWebResponse
     */
    abstract public function getResponse();

    /**
     * Gets request.
     *
     * @return \sfWebRequest
     */
    abstract public function getRequest();

    /**
     * Gets user.
     *
     * @return \sfUser
     */
    abstract public function getUser();

    /**
     * Gets the current exception.
     *
     * @return \Exception
     */
    public function getCurrentException()
    {
        return $this->currentException;
    }

    /**
     * Sets the current exception.
     *
     * @param \Exception $exception An Exception instance
     */
    public function setCurrentException(\Exception $exception)
    {
        $this->currentException = $exception;
    }

    /**
     * Resets the current exception.
     */
    public function resetCurrentException()
    {
        $this->currentException = null;
        \sfException::clearLastException();
    }

    /**
     * Test for an uncaught exception.
     *
     * @return bool
     */
    public function checkCurrentExceptionIsEmpty()
    {
        return null === $this->getCurrentException() || $this->getCurrentException() instanceof \sfError404Exception;
    }

    /**
     * Follow redirects?
     *
     * @return \sfBrowserBase
     *
     * @throws \sfException If request was not a redirect
     */
    public function followRedirect()
    {
        if (null === $this->getResponse()->getHttpHeader('Location')) {
            throw new \LogicException('The request was not redirected.');
        }

        return $this->get($this->getResponse()->getHttpHeader('Location'));
    }

    /**
     * Sets a form field in the browser.
     *
     * @param string $name  The field name
     * @param string $value The field value
     *
     * @return \sfBrowserBase
     */
    public function setField($name, $value)
    {
        // as we don't know yet the form, just store name/value pairs
        $this->parseArgumentAsArray($name, $value, $this->fields);

        return $this;
    }

    /**
     * Simulates deselecting a checkbox or radiobutton.
     *
     * @param string $name The checkbox or radiobutton id, name or text
     *
     * @return \sfBrowserBase
     *
     * @see    doSelect()
     */
    public function deselect($name)
    {
        $this->doSelect($name, false);

        return $this;
    }

    /**
     * Simulates selecting a checkbox or radiobutton.
     *
     * @param string $name The checkbox or radiobutton id, name or text
     *
     * @return \sfBrowserBase
     *
     * @see    doSelect()
     */
    public function select($name)
    {
        $this->doSelect($name, true);

        return $this;
    }

    /**
     * Simulates selecting a checkbox or radiobutton.
     *
     * This method is called internally by the select() and deselect() methods.
     *
     * @param string $name     The checkbox or radiobutton id, name or text
     * @param bool   $selected If true the item will be selected
     */
    public function doSelect($name, $selected)
    {
        $xpath = $this->getResponseDomXpath();

        if ($element = $xpath->query(sprintf('//input[(@type="radio" or @type="checkbox") and (.="%s" or @id="%s" or @name="%s")]', $name, $name, $name))->item(0)) {
            if ($selected) {
                if ('radio' == $element->getAttribute('type')) {
                    // we need to deselect all other radio buttons with the same name
                    foreach ($xpath->query(sprintf('//input[@type="radio" and @name="%s"]', $element->getAttribute('name'))) as $radio) {
                        $radio->removeAttribute('checked');
                    }
                }
                $element->setAttribute('checked', 'checked');
            } else {
                if ('radio' == $element->getAttribute('type')) {
                    throw new \InvalidArgumentException('Radiobutton cannot be deselected - Select another radiobutton to deselect the current.');
                }
                $element->removeAttribute('checked');
            }
        } else {
            throw new \InvalidArgumentException(sprintf('Cannot find the "%s" checkbox or radiobutton.', $name));
        }
    }

    /**
     * Simulates a click on a link or button.
     *
     * Available options:
     *
     *  * position: The position of the linked to link if several ones have the same name
     *              (the first one is 1, not 0)
     *  * method:   The method to used instead of the form ones
     *              (useful when you need to click on a link that is converted to a form with JavaScript code)
     *
     * @param DOMElement|string $name      The link, button text, CSS selector or DOMElement
     * @param array             $arguments The arguments to pass to the link
     * @param array             $options   An array of options
     *
     * @return \sfBrowserBase
     *
     * @uses   doClickElement() doClick() doClickCssSelector()
     */
    public function click($name, $arguments = [], $options = [])
    {
        if ($name instanceof \DOMElement) {
            list($uri, $method, $parameters) = $this->doClickElement($name, $arguments, $options);
        } else {
            try {
                list($uri, $method, $parameters) = $this->doClick($name, $arguments, $options);
            } catch (\InvalidArgumentException $e) {
                list($uri, $method, $parameters) = $this->doClickCssSelector($name, $arguments, $options);
            }
        }

        return $this->call($uri, $method, $parameters);
    }

    /**
     * Simulates a click on a link or button.
     *
     * This method is called internally by the {@link click()} method.
     *
     * @param string $name      The link or button text
     * @param array  $arguments The arguments to pass to the link
     * @param array  $options   An array of options
     *
     * @return array An array composed of the URI, the method and the arguments to pass to the {@link call()} call
     *
     * @uses   getResponseDomXpath() doClickElement()
     *
     * @throws \InvalidArgumentException If a matching element cannot be found
     *
     * @deprecated call {@link click()} using a CSS selector instead
     */
    public function doClick($name, $arguments = [], $options = [])
    {
        if (str_contains($name, '[') ||   str_contains($name, ']')) {
            throw new \InvalidArgumentException(sprintf('The name "%s" is not valid', $name));
        }

        $query = sprintf('//a[.="%s"]', $name);
        $query .= sprintf('|//a/img[@alt="%s"]/ancestor::a', $name);
        $query .= sprintf('|//input[((@type="submit" or @type="button") and @value="%s") or (@type="image" and @alt="%s")]', $name, $name);
        $query .= sprintf('|//button[.="%s" or @id="%s" or @name="%s"]', $name, $name, $name);

        if (!$list = @$this->getResponseDomXpath()->query($query)) {
            throw new \InvalidArgumentException(sprintf('The name "%s" is not valid', $name));
        }

        $position = isset($options['position']) ? $options['position'] - 1 : 0;

        if (!$item = $list->item($position)) {
            throw new \InvalidArgumentException(sprintf('Cannot find the "%s" link or button (position %d).', $name, $position + 1));
        }

        return $this->doClickElement($item, $arguments, $options);
    }

    /**
     * Simulates a click on an element indicated by CSS selector.
     *
     * This method is called internally by the {@link click()} method.
     *
     * @param string $selector  The CSS selector
     * @param array  $arguments The arguments to pass to the link
     * @param array  $options   An array of options
     *
     * @return array An array composed of the URI, the method and the arguments to pass to the {@link call()} call
     *
     * @uses   getResponseDomCssSelector() doClickElement()
     *
     * @throws \InvalidArgumentException If a matching element cannot be found
     */
    public function doClickCssSelector($selector, $arguments = [], $options = [])
    {
        $elements = $this->getResponseDomCssSelector()->matchAll($selector)->getNodes();
        $position = isset($options['position']) ? $options['position'] - 1 : 0;

        if (isset($elements[$position])) {
            return $this->doClickElement($elements[$position], $arguments, $options);
        }

        throw new \InvalidArgumentException(sprintf('Could not find the element "%s" (position %d) in the current DOM.', $selector, $position + 1));
    }

    /**
     * Simulates a click on the supplied DOM element.
     *
     * This method is called internally by the {@link click()} method.
     *
     * @param \DOMElement $item      The element being clicked
     * @param array       $arguments The arguments to pass to the link
     * @param array       $options   An array of options
     *
     * @return array An array composed of the URI, the method and the arguments to pass to the call() call
     *
     * @uses getResponseDomXpath()
     */
    public function doClickElement(\DOMElement $item, $arguments = [], $options = [])
    {
        $method = strtolower(isset($options['method']) ? $options['method'] : 'get');

        if ('a' == $item->nodeName) {
            if (in_array($method, ['post', 'put', 'delete'])) {
                if (isset($options['_with_csrf']) && $options['_with_csrf']) {
                    $arguments['_with_csrf'] = true;
                }

                return [$item->getAttribute('href'), $method, $arguments];
            }

            return [$item->getAttribute('href'), 'get', $arguments];
        }
        if ('button' == $item->nodeName || ('input' == $item->nodeName && in_array($item->getAttribute('type'), ['submit', 'button', 'image']))) {
            // add the item's value to the arguments if name is provided
            if ($item->getAttribute('name')) {
                $this->parseArgumentAsArray($item->getAttribute('name'), $item->getAttribute('value'), $arguments);
            }

            // use the ancestor form element
            do {
                if (null === $item = $item->parentNode) {
                    throw new \Exception('The clicked form element does not have a form ancestor.');
                }
            } while ('form' != $item->nodeName);
        }

        // form attributes
        $url = $item->getAttribute('action');
        if (!$url || '#' == $url) {
            $url = $this->stack[$this->stackPosition]['uri'];
        }
        $method = strtolower(isset($options['method']) ? $options['method'] : ($item->getAttribute('method') ?: 'get'));

        // merge form default values and arguments
        $defaults = [];
        $arguments = \sfToolkit::arrayDeepMerge($this->fields, $arguments);

        $xpath = $this->getResponseDomXpath();
        foreach ($xpath->query('descendant::input | descendant::textarea | descendant::select', $item) as $element) {
            if ($element->hasAttribute('disabled')) {
                continue;
            }

            $elementName = $element->getAttribute('name');
            $nodeName = $element->nodeName;
            $value = null;

            if ('input' == $nodeName && ('checkbox' == $element->getAttribute('type') || 'radio' == $element->getAttribute('type'))) {
                if ($element->getAttribute('checked')) {
                    $value = $element->hasAttribute('value') ? $element->getAttribute('value') : '1';
                }
            } elseif ('input' == $nodeName && 'file' == $element->getAttribute('type')) {
                $filename = array_key_exists($elementName, $arguments) ? $arguments[$elementName] : \sfToolkit::getArrayValueForPath($arguments, $elementName, '');

                if (is_readable($filename)) {
                    $fileError = UPLOAD_ERR_OK;
                    $fileSize = filesize($filename);
                } else {
                    $fileError = UPLOAD_ERR_NO_FILE;
                    $fileSize = 0;
                }

                unset($arguments[$elementName]);

                $this->parseArgumentAsArray($elementName, ['name' => basename($filename), 'type' => '', 'tmp_name' => $filename, 'error' => $fileError, 'size' => $fileSize], $this->files);
            } elseif ('input' == $nodeName && !in_array($element->getAttribute('type'), ['submit', 'button', 'image'])) {
                $value = $element->getAttribute('value');
            } elseif ('textarea' == $nodeName) {
                $value = '';
                foreach ($element->childNodes as $el) {
                    $value .= $this->getResponseDom()->saveXML($el);
                }
            } elseif ('select' == $nodeName) {
                if ($multiple = $element->hasAttribute('multiple')) {
                    $elementName = str_replace('[]', '', $elementName);
                    $value = [];
                } else {
                    $value = null;
                }

                $found = false;
                foreach ($xpath->query('descendant::option', $element) as $option) {
                    if ($option->getAttribute('selected')) {
                        $found = true;
                        if ($multiple) {
                            $value[] = $option->getAttribute('value');
                        } else {
                            $value = $option->getAttribute('value');
                        }
                    }
                }

                // if no option is selected and if it is a simple select box, take the first option as the value
                $option = $xpath->query('descendant::option', $element)->item(0);
                if (!$found && !$multiple && $option instanceof \DOMElement) {
                    $value = $option->getAttribute('value');
                }
            }

            if (null !== $value) {
                $this->parseArgumentAsArray($elementName, $value, $defaults);
            }
        }

        // create request parameters
        $arguments = \sfToolkit::arrayDeepMerge($defaults, $arguments);
        if (in_array($method, ['post', 'put', 'delete'])) {
            return [$url, $method, $arguments];
        }

        $queryString = is_array($arguments) ? http_build_query($arguments, '', '&') : '';
        $sep =   !str_contains($url, '?') ? '?' : '&';

        return [$url.($queryString ? $sep.$queryString : ''), 'get', []];
    }

    /**
     * Reset browser to original state.
     *
     * @return \sfBrowserBase
     */
    public function restart()
    {
        $this->newSession();
        $this->cookieJar = [];
        $this->stack = [];
        $this->fields = [];
        $this->vars = [];
        $this->dom = null;
        $this->stackPosition = -1;

        return $this;
    }

    /**
     * Shutdown function to clean up and remove sessions.
     */
    public function shutdown()
    {
        $this->checkCurrentExceptionIsEmpty();
    }

    /**
     * Fixes uri removing # declarations and front controller.
     *
     * @param string $uri The URI to fix
     *
     * @return string The fixed uri
     */
    public function fixUri($uri)
    {
        // remove absolute information if needed (to be able to do follow redirects, click on links, ...)
        if (str_starts_with($uri, 'http')) {
            // detect secure request
            if (str_starts_with($uri, 'https')) {
                $this->defaultServerArray['HTTPS'] = 'on';
            } else {
                unset($this->defaultServerArray['HTTPS']);
            }

            $uri = preg_replace('#^https?\://[^/]+/#', '/', $uri);
        }
        $uri = str_replace('/index.php', '', $uri);

        // # as a uri
        if ($uri && '#' == $uri[0]) {
            $uri = $this->stack[$this->stackPosition]['uri'].$uri;
        }

        return $uri;
    }

    /**
     * Calls a request to a uri.
     */
    abstract protected function doCall();

    /**
     * Parses arguments as array.
     *
     * @param string $name  The argument name
     * @param string $value The argument value
     * @param array  $vars
     */
    protected function parseArgumentAsArray($name, $value, &$vars)
    {
        if (false !== $pos = strpos($name, '[')) {
            $var = &$vars;
            $tmps = array_filter(preg_split('/(\[ | \[\] | \])/x', $name), function ($s) { return '' !== $s; });
            foreach ($tmps as $tmp) {
                $var = &$var[$tmp];
            }
            if ($var && '[]' === substr($name, -2)) {
                if (!is_array($var)) {
                    $var = [$var];
                }
                $var[] = $value;
            } else {
                $var = $value;
            }
        } else {
            $vars[$name] = $value;
        }
    }

    /**
     * Creates a new session in the browser.
     */
    protected function newSession()
    {
        $this->defaultServerArray['session_id'] = $_SERVER['session_id'] = md5(uniqid(mt_rand(), true));
    }
}
