<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfNoRouting class is a very simple routing class that uses GET parameters.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id: sfNoRouting.class.php 20566 2009-07-29 07:04:01Z fabien $
 */
class sfNoRouting extends sfRouting
{
    /**
     * @see sfRouting
     *
     * @param mixed $with_route_name
     */
    public function getCurrentInternalUri($with_route_name = false)
    {
        $parameters = $this->mergeArrays($this->defaultParameters, $_GET);
        $action = sprintf('%s/%s', $parameters['module'], $parameters['action']);

        // other parameters
        unset($parameters['module'], $parameters['action']);
        ksort($parameters);
        $parameters = count($parameters) ? '?'.http_build_query($parameters, '', '&') : '';

        return sprintf('%s%s', $action, $parameters);
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
        $parameters = $this->mergeArrays($this->defaultParameters, $params);
        if ($this->getDefaultParameter('module') == $parameters['module']) {
            unset($parameters['module']);
        }
        if ($this->getDefaultParameter('action') == $parameters['action']) {
            unset($parameters['action']);
        }

        $parameters = http_build_query($parameters, '', '&');

        return $this->fixGeneratedUrl('/'.($parameters ? '?'.$parameters : ''), $absolute);
    }

    /**
     * @see sfRouting
     *
     * @param mixed $url
     */
    public function parse($url)
    {
        return [];
    }

    /**
     * @see sfRouting
     */
    public function getRoutes()
    {
        return [];
    }

    /**
     * @see sfRouting
     *
     * @param mixed $name
     */
    public function getRoute($name)
    {
        return null;
    }

    /**
     * @see sfRouting
     *
     * @param mixed $routes
     */
    public function setRoutes($routes)
    {
        return [];
    }

    /**
     * @see sfRouting
     */
    public function hasRoutes()
    {
        return false;
    }

    /**
     * @see sfRouting
     */
    public function clearRoutes()
    {
    }

    protected function mergeArrays($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            $arr1[$key] = $value;
        }

        return $arr1;
    }
}
