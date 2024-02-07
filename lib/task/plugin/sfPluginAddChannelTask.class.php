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
 * Installs a plugin.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPluginAddChannelTask extends \sfPluginBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->addArguments([
            new \sfCommandArgument('name', \sfCommandArgument::REQUIRED, 'The channel name'),
        ]);

        $this->namespace = 'plugin';
        $this->name = 'add-channel';

        $this->briefDescription = 'Add a new PEAR channel';

        $this->detailedDescription = <<<'EOF'
The [plugin:add-channel|INFO] task adds a new PEAR channel:

  [./symfony plugin:add-channel symfony.plugins.pear.example.com|INFO]
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        $this->logSection('plugin', sprintf('add channel "%s"', $arguments['name']));

        $this->getPluginManager()->getEnvironment()->registerChannel($arguments['name']);
    }
}
