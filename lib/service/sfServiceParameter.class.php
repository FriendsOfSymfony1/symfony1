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
 * sfServiceParameter represents a parameter reference.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id: sfServiceReference.php 267 2009-03-26 19:56:18Z fabien $
 */
class sfServiceParameter
{
    protected $id;

    /**
     * Constructor.
     *
     * @param string $id The parameter key
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * __toString.
     *
     * @return string The parameter key
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
