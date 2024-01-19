<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfTester is the base class for all tester classes.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfTester
{
    protected $inABlock = false;
    protected $browser;
    protected $tester;

    /**
     * Constructor.
     *
     * @param sfTestFunctionalBase $browser A browser
     * @param lime_test            $tester  A tester object
     */
    public function __construct(sfTestFunctionalBase $browser, $tester)
    {
        $this->browser = $browser;
        $this->tester = $tester;
    }

    public function __call($method, $arguments)
    {
        call_user_func_array([$this->browser, $method], $arguments);

        return $this->getObjectToReturn();
    }

    /**
     * Prepares the tester.
     */
    abstract public function prepare();

    /**
     * Initializes the tester.
     */
    abstract public function initialize();

    /**
     * Begins a block.
     *
     * @return sfTester This sfTester instance
     */
    public function begin()
    {
        $this->inABlock = true;

        return $this->browser->begin();
    }

    /**
     * Ends a block.
     *
     * @param sfTestFunctionalBase
     */
    public function end()
    {
        $this->inABlock = false;

        return $this->browser->end();
    }

    /**
     * Returns the object that each test method must return.
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function getObjectToReturn()
    {
        return $this->inABlock ? $this : $this->browser;
    }
}
