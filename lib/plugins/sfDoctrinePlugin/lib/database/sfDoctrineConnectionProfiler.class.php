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
 * Connection profiler.
 *
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineConnectionProfiler extends \Doctrine_Connection_Profiler
{
    protected $dispatcher;
    protected $options = [];

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * logging:              Whether to notify query logging events (defaults to false)
     *  * slow_query_threshold: How many seconds a query must take to be considered slow (defaults to 1)
     *
     * @param array $options
     */
    public function __construct(\sfEventDispatcher $dispatcher, $options = [])
    {
        $this->dispatcher = $dispatcher;
        $this->options = array_merge([
            'logging' => false,
            'slow_query_threshold' => 1,
        ], $options);
    }

    /**
     * Returns an option value.
     *
     * @param string $name
     */
    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * Sets an option value.
     *
     * @param string $name
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Logs time and a connection query on behalf of the connection.
     */
    public function preQuery(\Doctrine_Event $event)
    {
        if ($this->options['logging']) {
            $this->dispatcher->notify(new \sfEvent($event->getInvoker(), 'application.log', [sprintf('query : %s - (%s)', $event->getQuery(), join(', ', self::fixParams($event->getParams())))]));
        }

        \sfTimerManager::getTimer('Database (Doctrine)');

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);
    }

    /**
     * Logs to the timer.
     */
    public function postQuery(\Doctrine_Event $event)
    {
        \sfTimerManager::getTimer('Database (Doctrine)', false)->addTime();

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);

        if ($event->getElapsedSecs() > $this->options['slow_query_threshold']) {
            $event->slowQuery = true;
        }
    }

    /**
     * Logs a connection exec on behalf of the connection.
     */
    public function preExec(\Doctrine_Event $event)
    {
        if ($this->options['logging']) {
            $this->dispatcher->notify(new \sfEvent($event->getInvoker(), 'application.log', [sprintf('exec : %s - (%s)', $event->getQuery(), join(', ', self::fixParams($event->getParams())))]));
        }

        \sfTimerManager::getTimer('Database (Doctrine)');

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);
    }

    /**
     * Logs to the timer.
     */
    public function postExec(\Doctrine_Event $event)
    {
        \sfTimerManager::getTimer('Database (Doctrine)', false)->addTime();

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);

        if ($event->getElapsedSecs() > $this->options['slow_query_threshold']) {
            $event->slowQuery = true;
        }
    }

    /**
     * Logs a statement execute on behalf of the statement.
     */
    public function preStmtExecute(\Doctrine_Event $event)
    {
        if ($this->options['logging']) {
            $this->dispatcher->notify(new \sfEvent($event->getInvoker(), 'application.log', [sprintf('execute : %s - (%s)', $event->getQuery(), join(', ', self::fixParams($event->getParams())))]));
        }

        \sfTimerManager::getTimer('Database (Doctrine)');

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);
    }

    /**
     * Logs to the timer.
     */
    public function postStmtExecute(\Doctrine_Event $event)
    {
        \sfTimerManager::getTimer('Database (Doctrine)', false)->addTime();

        $args = func_get_args();
        $this->__call(__FUNCTION__, $args);

        if ($event->getElapsedSecs() > $this->options['slow_query_threshold']) {
            $event->slowQuery = true;
        }
    }

    /**
     * Returns events having to do with query execution.
     *
     * @return array
     */
    public function getQueryExecutionEvents()
    {
        $events = [];
        foreach ($this as $event) {
            if (in_array($event->getCode(), [\Doctrine_Event::CONN_QUERY, \Doctrine_Event::CONN_EXEC, \Doctrine_Event::STMT_EXECUTE])) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * Fixes query parameters for logging.
     *
     * @param array $params
     *
     * @return array
     */
    public static function fixParams($params)
    {
        if (!is_array($params)) {
            return [];
        }

        foreach ($params as $key => $param) {
            if ($param && strlen($param) >= 255) {
                $params[$key] = '['.number_format(strlen($param) / 1024, 2).'Kb]';
            }
        }

        return $params;
    }
}
