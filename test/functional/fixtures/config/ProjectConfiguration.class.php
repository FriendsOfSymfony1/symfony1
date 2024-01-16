<?php

require_once __DIR__.'/../../../../lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
    public function setup()
    {
        $this->enableAllPluginsExcept(['sfDoctrinePlugin']);
        $this->enablePlugins('sfAutoloadPlugin');
    }
}
