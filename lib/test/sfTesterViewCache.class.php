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
 * sfTesterViewCache implements tests for the symfony view cache manager.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTesterViewCache extends \sfTester
{
    protected $viewCacheManager;
    protected $response;
    protected $routing;

    /**
     * Prepares the tester.
     */
    public function prepare()
    {
    }

    /**
     * Initializes the tester.
     */
    public function initialize()
    {
        $this->viewCacheManager = $this->browser->getContext()->getViewCacheManager();
        $this->routing = $this->browser->getContext()->getRouting();
        $this->response = $this->browser->getResponse();
    }

    /**
     * Tests if the given uri is cached.
     *
     * @param bool $boolean     Flag for checking the cache
     * @param bool $with_layout If have or not layout
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isCached($boolean, $with_layout = false)
    {
        return $this->isUriCached($this->viewCacheManager->getCurrentCacheKey(), $boolean, $with_layout);
    }

    /**
     * Tests if the given uri is cached.
     *
     * @param string $uri         Uniform resource identifier
     * @param bool   $boolean     Flag for checking the cache
     * @param bool   $with_layout If have or not layout
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isUriCached($uri, $boolean, $with_layout = false)
    {
        $cacheManager = $this->viewCacheManager;

        // check that cache is enabled
        if (!$cacheManager) {
            $this->tester->ok(!$boolean, 'cache is disabled');

            return $this->getObjectToReturn();
        }

        if ($uri == $this->viewCacheManager->getCurrentCacheKey()) {
            $main = true;
            $type = $with_layout ? 'page' : 'action';
        } else {
            $main = false;
            $type = $uri;
        }

        // check layout configuration
        if ($cacheManager->withLayout($uri) && !$with_layout) {
            $this->tester->fail('cache without layout');
            $this->tester->skip('cache is not configured properly', 2);
        } elseif (!$cacheManager->withLayout($uri) && $with_layout) {
            $this->tester->fail('cache with layout');
            $this->tester->skip('cache is not configured properly', 2);
        } else {
            $this->tester->pass('cache is configured properly');

            // check page is cached
            $ret = $this->tester->is($cacheManager->has($uri), $boolean, sprintf('"%s" %s in cache', $type, $boolean ? 'is' : 'is not'));

            // check that the content is ok in cache
            if ($boolean) {
                if (!$ret) {
                    $this->tester->fail('content in cache is ok');
                } elseif ($with_layout) {
                    $response = unserialize($cacheManager->get($uri));
                    $content = $response->getContent();
                    $this->tester->ok($content == $this->response->getContent(), 'content in cache is ok');
                } else {
                    $ret = unserialize($cacheManager->get($uri));
                    $content = $ret['content'];
                    $this->tester->ok(str_contains($this->response->getContent(), $content), 'content in cache is ok');
                }
            }
        }

        return $this->getObjectToReturn();
    }
}
