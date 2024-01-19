<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * defaultActions module.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class defaultActions extends sfActions
{
    /**
     * Congratulations page for creating an application.
     */
    public function executeIndex()
    {
    }

    /**
     * Congratulations page for creating a module.
     */
    public function executeModule()
    {
    }

    /**
     * Error page for page not found (404) error.
     */
    public function executeError404()
    {
    }

    /**
     * Warning page for restricted area - requires login.
     */
    public function executeSecure()
    {
    }

    /**
     * Warning page for restricted area - requires credentials.
     */
    public function executeLogin()
    {
    }

    /**
     * Module disabled in settings.yml.
     */
    public function executeDisabled()
    {
    }
}
