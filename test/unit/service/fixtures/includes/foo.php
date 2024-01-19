<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class FooClass
{
    public $bar;
    public $initialized = false;
    public $configured = false;
    public $called = false;
    public $arguments = [];

    public function __construct($arguments = [])
    {
        $this->arguments = $arguments;
    }

    public static function getInstance($arguments = [])
    {
        $obj = new self($arguments);
        $obj->called = true;

        return $obj;
    }

    public function initialize()
    {
        $this->initialized = true;
    }

    public function configure()
    {
        $this->configured = true;
    }

    public function setBar($value = null)
    {
        $this->bar = $value;
    }
}
