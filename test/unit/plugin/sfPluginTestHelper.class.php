<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class sfPluginTestHelper
{
    public static function convertUrlToFixture($url)
    {
        $file = preg_replace(['/_+/', '#/+#', '#_/#'], ['_', '/', '/'], preg_replace('#[^a-zA-Z0-9\-/\.]#', '_', $url));

        $dir = dirname($file);
        $file = basename($file);

        $dest = SF_PLUGIN_TEST_DIR.'/'.$dir.'/'.$file;

        if (!is_dir(dirname($dest))) {
            mkdir(dirname($dest), 0777, true);
        }

        if (!file_exists(__DIR__.'/fixtures/'.$dir.'/'.$file)) {
            throw new \sfException(sprintf('Unable to find fixture for %s (%s)', $url, $file));
        }

        copy(__DIR__.'/fixtures/'.$dir.'/'.$file, $dest);

        return $dest;
    }
}
