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
 * Abstract output escaping decorator class for "getter" objects.
 *
 * @see        \sfOutputEscaper
 *
 * @author     Mike Squire <mike@somosis.co.uk>
 *
 * @version    SVN: $Id$
 */
abstract class sfOutputEscaperGetterDecorator extends \sfOutputEscaper
{
    /**
     * Returns the raw, unescaped value associated with the key supplied.
     *
     * The key might be an index into an array or a value to be passed to the
     * decorated object's get() method.
     *
     * @param string $key The key to retrieve
     *
     * @return mixed The value
     */
    abstract public function getRaw($key);

    /**
     * Returns the escaped value associated with the key supplied.
     *
     * Typically (using this implementation) the raw value is obtained using the
     * {@link getRaw()} method, escaped and the result returned.
     *
     * @param string $key            The key to retieve
     * @param string $escapingMethod The escaping method (a PHP function) to use
     *
     * @return mixed The escaped value
     */
    public function get($key, $escapingMethod = null)
    {
        if (!$escapingMethod) {
            $escapingMethod = $this->escapingMethod;
        }

        return \sfOutputEscaper::escape($escapingMethod, $this->getRaw($key));
    }
}
