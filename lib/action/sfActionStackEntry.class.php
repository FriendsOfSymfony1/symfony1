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
 * sfActionStackEntry represents information relating to a single sfAction request during a single HTTP request.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 *
 * @version    SVN: $Id$
 */
class sfActionStackEntry
{
    protected $actionInstance;
    protected $actionName;
    protected $moduleName;
    protected $presentation;

    /**
     * Class constructor.
     *
     * @param string    $moduleName     A module name
     * @param string    $actionName     An action name
     * @param \sfAction $actionInstance An sfAction implementation instance
     */
    public function __construct($moduleName, $actionName, $actionInstance)
    {
        $this->actionName = $actionName;
        $this->actionInstance = $actionInstance;
        $this->moduleName = $moduleName;
    }

    /**
     * Retrieves this entry's action name.
     *
     * @return string An action name
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Retrieves this entry's action instance.
     *
     * @return \sfAction An sfAction implementation instance
     */
    public function getActionInstance()
    {
        return $this->actionInstance;
    }

    /**
     * Retrieves this entry's module name.
     *
     * @return string A module name
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Retrieves this entry's rendered view presentation.
     *
     * This will only exist if the view has processed and the render mode is set to sfView::RENDER_VAR.
     *
     * @return string Rendered view presentation
     */
    public function &getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Sets the rendered presentation for this action.
     *
     * @param string $presentation a rendered presentation
     */
    public function setPresentation(&$presentation)
    {
        $this->presentation = &$presentation;
    }
}
