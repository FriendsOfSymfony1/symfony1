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
 * sfWebDebugPanelMemory adds a panel to the web debug toolbar with the memory used by the script.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWebDebugPanelMemory extends \sfWebDebugPanel
{
    public function getTitle()
    {
        $totalMemory = sprintf('%.1f', memory_get_peak_usage(true) / 1024);

        return '<img src="'.$this->webDebug->getOption('image_root_path').'/memory.png" alt="Memory" /> '.$totalMemory.' KB';
    }

    public function getPanelTitle()
    {
    }

    public function getPanelContent()
    {
    }
}
