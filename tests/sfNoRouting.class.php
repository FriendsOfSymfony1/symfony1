<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
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
     */
    public function generate($name, $params = array(), $absolute = false)
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
     */
    public function parse($url)
    {
        return array();
    }

    /**
     * @see sfRouting
     */
    public function getRoutes()
    {
        return array();
    }

    /**
     * @see sfRouting
     */
    public function getRoute($name)
    {
        return null;
    }

    /**
     * @see sfRouting
     */
    public function setRoutes($routes)
    {
        return array();
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
    public function clearRoutes() {}

    protected function mergeArrays($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            $arr1[$key] = $value;
        }

        return $arr1;
    }
}
