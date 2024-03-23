<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfActionStack keeps a list of all requested actions and provides accessor
 * methods for retrieving individual entries.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 */
class sfActionStack
{
    /** @var sfActionStackEntry[] */
    protected $stack = [];

    /**
     * Adds an entry to the action stack.
     *
     * @param string   $moduleName     A module name
     * @param string   $actionName     An action name
     * @param sfAction $actionInstance An sfAction implementation instance
     *
     * @return sfActionStackEntry sfActionStackEntry instance
     */
    public function addEntry($moduleName, $actionName, $actionInstance)
    {
        // create our action stack entry and add it to our stack
        $actionEntry = new sfActionStackEntry($moduleName, $actionName, $actionInstance);

        $this->stack[] = $actionEntry;

        return $actionEntry;
    }

    /**
     * Retrieves the entry at a specific index.
     *
     * @param int $index An entry index
     *
     * @return sfActionStackEntry an action stack entry implementation
     */
    public function getEntry($index)
    {
        return $this->stack[$index] ?? null;
    }

    /**
     * Removes the entry at a specific index.
     *
     * @return sfActionStackEntry an action stack entry implementation
     */
    public function popEntry()
    {
        return array_pop($this->stack);
    }

    /**
     * Retrieves the first entry.
     *
     * @return mixed An action stack entry implementation or null if there is no sfAction instance in the stack
     */
    public function getFirstEntry()
    {
        return $this->stack[0] ?? null;
    }

    /**
     * Retrieves the last entry.
     *
     * @return mixed An action stack entry implementation or null if there is no sfAction instance in the stack
     */
    public function getLastEntry()
    {
        return $this->stack[count($this->stack) - 1] ?? null;
    }

    /**
     * Retrieves the size of this stack.
     *
     * @return int the size of this stack
     */
    public function getSize()
    {
        return count($this->stack);
    }
}
