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
 * sfRequestRoute represents a route that is request aware.
 *
 * It implements the sf_method requirement.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfRequestRoute extends \sfRoute
{
    /**
     * Constructor.
     *
     * Applies a default sf_method requirements of GET or HEAD.
     *
     * @see \sfRoute
     */
    public function __construct($pattern, $defaults = [], $requirements = [], $options = [])
    {
        if (!isset($requirements['sf_method'])) {
            $requirements['sf_method'] = ['get', 'head'];
        } else {
            $requirements['sf_method'] = array_map('strtolower', (array) $requirements['sf_method']);
        }

        parent::__construct($pattern, $defaults, $requirements, $options);
    }

    /**
     * Returns true if the URL matches this route, false otherwise.
     *
     * @param string $url     The URL
     * @param array  $context The context
     *
     * @return array|bool An array of parameters or false if not matching
     */
    public function matchesUrl($url, $context = [])
    {
        if (false === $parameters = parent::matchesUrl($url, $context)) {
            return false;
        }

        // enforce the sf_method requirement
        if (in_array(strtolower($context['method']), $this->requirements['sf_method'])) {
            return $parameters;
        }

        return false;
    }

    /**
     * Returns true if the parameters match this route, false otherwise.
     *
     * @param mixed $params  The parameters
     * @param array $context The context
     *
     * @return bool true if the parameters match this route, false otherwise
     */
    public function matchesParameters($params, $context = [])
    {
        if (isset($params['sf_method'])) {
            // enforce the sf_method requirement
            if (!in_array(strtolower($params['sf_method']), $this->requirements['sf_method'])) {
                return false;
            }

            unset($params['sf_method']);
        }

        return parent::matchesParameters($params, $context);
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param mixed $params   The parameter values
     * @param array $context  The context
     * @param bool  $absolute Whether to generate an absolute URL
     *
     * @return string The generated URL
     */
    public function generate($params, $context = [], $absolute = false)
    {
        unset($params['sf_method']);

        return parent::generate($params, $context, $absolute);
    }
}
