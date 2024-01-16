<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPearRestTest is a class to be able to test a PEAR channel without the HTTP layer.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 *
 * @internal
 *
 * @coversNothing
 */
class sfPearRestTest extends sfPearRest
{
    /**
     * @see PEAR_REST::downloadHttp()
     *
     * @param mixed|null $lastmodified
     * @param mixed      $url
     * @param mixed      $accept
     * @param mixed      $channel
     */
    public function downloadHttp($url, $lastmodified = null, $accept = false, $channel = false)
    {
        try {
            $file = sfPluginTestHelper::convertUrlToFixture($url);
        } catch (sfException $e) {
            return PEAR::raiseError($e->getMessage());
        }

        $headers = [
            'content-type' => preg_match('/\.xml$/', $file) ? 'text/xml' : 'text/plain',
        ];

        return [file_get_contents($file), 0, $headers];
    }

    // Disable caching for testing
    public function saveCache($url, $contents, $lastmodified, $nochange = false, $cacheid = null)
    {
        return false;
    }
}
