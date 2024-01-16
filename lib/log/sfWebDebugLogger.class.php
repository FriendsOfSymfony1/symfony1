<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebDebugLogger logs messages into the web debug toolbar.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWebDebugLogger extends sfVarLogger
{
    /** @var sfContext */
    protected $context;

    /** @var string */
    protected $webDebugClass;

    /** @var sfWebDebug */
    protected $webDebug;

    /**
     * Initializes this logger.
     *
     * Available options:
     *
     *  * web_debug_class: The web debug class (sfWebDebug by default)
     *
     * @param sfEventDispatcher $dispatcher A sfEventDispatcher instance
     * @param array             $options    an array of options
     *
     * @see sfVarLogger
     */
    public function initialize(sfEventDispatcher $dispatcher, $options = [])
    {
        $this->context = sfContext::getInstance();

        $this->webDebugClass = isset($options['web_debug_class']) ? $options['web_debug_class'] : 'sfWebDebug';

        if (sfConfig::get('sf_web_debug')) {
            $dispatcher->connect('context.load_factories', [$this, 'listenForLoadFactories']);
            $dispatcher->connect('response.filter_content', [$this, 'filterResponseContent']);
        }

        $this->registerErrorHandler();

        parent::initialize($dispatcher, $options);
    }

    /**
     * PHP error handler send PHP errors to log.
     *
     * PHP user space error handler can not handle E_ERROR, E_PARSE,
     * E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING,
     * and most of E_STRICT.
     *
     * @param string $errno      the level of the error raised, as an integer
     * @param string $errstr     the error message, as a string
     * @param string $errfile    the filename that the error was raised in, as a string
     * @param string $errline    the line number the error was raised at, as an integer
     * @param array  $errcontext an array that points to the active symbol table at the point the error occurred
     *
     * @return bool
     */
    public function handlePhpError($errno, $errstr, $errfile, $errline, $errcontext = [])
    {
        if (($errno & error_reporting()) == 0) {
            return false;
        }

        $message = sprintf(' %%s at %s on line %s (%s)', $errfile, $errline, str_replace('%', '%%', $errstr));

        switch ($errno) {
            case E_STRICT:
                $this->dispatcher->notify(new sfEvent($this, 'application.log', ['priority' => sfLogger::ERR, sprintf($message, 'Strict notice')]));

                break;

            case E_NOTICE:
                $this->dispatcher->notify(new sfEvent($this, 'application.log', ['priority' => sfLogger::NOTICE, sprintf($message, 'Notice')]));

                break;

            case E_WARNING:
                $this->dispatcher->notify(new sfEvent($this, 'application.log', ['priority' => sfLogger::WARNING, sprintf($message, 'Warning')]));

                break;

            case E_RECOVERABLE_ERROR:
                $this->dispatcher->notify(new sfEvent($this, 'application.log', ['priority' => sfLogger::ERR, sprintf($message, 'Error')]));

                break;
        }

        return false; // do not prevent default error handling
    }

    /**
     * Listens for the context.load_factories event.
     */
    public function listenForLoadFactories(sfEvent $event)
    {
        $path = sprintf('%s/%s/images', $event->getSubject()->getRequest()->getRelativeUrlRoot(), sfConfig::get('sf_web_debug_web_dir'));
        $path = str_replace('//', '/', $path);

        $this->webDebug = new $this->webDebugClass($this->dispatcher, $this, [
            'image_root_path' => $path,
            'request_parameters' => $event->getSubject()->getRequest()->getParameterHolder()->getAll(),
        ]);
    }

    /**
     * Listens to the response.filter_content event.
     *
     * @param sfEvent $event   The sfEvent instance
     * @param string  $content The response content
     *
     * @return string The filtered response content
     */
    public function filterResponseContent(sfEvent $event, $content)
    {
        if (!sfConfig::get('sf_web_debug')) {
            return $content;
        }

        // log timers information
        $messages = [];
        foreach (sfTimerManager::getTimers() as $name => $timer) {
            $messages[] = sprintf('%s %.2f ms (%d)', $name, $timer->getElapsedTime() * 1000, $timer->getCalls());
        }
        $this->dispatcher->notify(new sfEvent($this, 'application.log', $messages));

        // don't add debug toolbar:
        // * for XHR requests
        // * if response status code is in the 3xx range
        // * if not rendering to the client
        // * if HTTP headers only
        $response = $event->getSubject();

        /** @var sfWebRequest $request */
        $request = $this->context->getRequest();
        if (
            null === $this->webDebug
            || !$this->context->has('request')
            || !$this->context->has('response')
            || !$this->context->has('controller')
            || $request->isXmlHttpRequest()
            || false === strpos($response->getContentType(), 'html')
            || '3' == substr($response->getStatusCode(), 0, 1)
            || sfView::RENDER_CLIENT != $this->context->getController()->getRenderMode()
            || $response->isHeaderOnly()
        ) {
            return $content;
        }

        return $this->webDebug->injectToolbar($content);
    }

    /**
     * Registers logger with PHP error handler.
     */
    protected function registerErrorHandler()
    {
        set_error_handler([$this, 'handlePhpError']);
    }
}
