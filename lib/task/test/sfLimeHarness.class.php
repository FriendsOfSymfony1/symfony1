<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../vendor/lime/lime.php';

class sfLimeHarness extends \lime_harness
{
    protected $plugins = [];

    public function addPlugins($plugins)
    {
        foreach ($plugins as $plugin) {
            $pluginDir = $plugin->getRootDir().DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR;

            $this->plugins[$pluginDir] = '['.preg_replace('/Plugin$/i', '', $plugin->getName()).'] ';

            if (true === $this->full_output) {
                $this->plugins[$pluginDir] .= $pluginDir;
            }
        }
    }

    protected function get_relative_file($file)
    {
        $file = strtr($file, $this->plugins);

        if (true === $this->full_output) {
            return $file;
        }

        return str_replace(DIRECTORY_SEPARATOR, '/', str_replace([realpath($this->base_dir).DIRECTORY_SEPARATOR, $this->extension], '', $file));
    }
}
