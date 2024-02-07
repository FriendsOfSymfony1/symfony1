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
 * sfPluginConfigurationGeneric represents a configuration for a plugin with no configuration class.
 *
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPluginConfigurationGeneric extends \sfPluginConfiguration
{
    /**
     * @see \sfPluginConfiguration
     */
    public function initialize()
    {
        return false;
    }
}
