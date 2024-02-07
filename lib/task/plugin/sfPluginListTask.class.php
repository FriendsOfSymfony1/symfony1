<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/sfPluginBaseTask.class.php';

/**
 * Lists installed plugins.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPluginListTask extends \sfPluginBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->namespace = 'plugin';
        $this->name = 'list';

        $this->briefDescription = 'Lists installed plugins';

        $this->detailedDescription = <<<'EOF'
The [plugin:list|INFO] task lists all installed plugins:

  [./symfony plugin:list|INFO]

It also gives the channel and version for each plugin.
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        $this->log($this->formatter->format('Installed plugins:', 'COMMENT'));

        foreach ($this->getPluginManager()->getInstalledPlugins() as $package) {
            $alias = $this->getPluginManager()->getEnvironment()->getRegistry()->getChannel($package->getChannel())->getAlias();
            $this->log(sprintf(' %-40s %10s-%-6s %s', $this->formatter->format($package->getPackage(), 'INFO'), $package->getVersion(), $package->getState() ?: null, $this->formatter->format(sprintf('# %s (%s)', $package->getChannel(), $alias), 'COMMENT')));
        }
    }
}
