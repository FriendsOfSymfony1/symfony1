<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../../../lib/autoload/sfCoreAutoload.class.php';
\sfCoreAutoload::register();

class ProjectConfiguration extends \sfProjectConfiguration
{
    public function setup()
    {
        $this->enableAllPluginsExcept(['sfDoctrinePlugin']);
        $this->enablePlugins('sfAutoloadPlugin');
    }
}
