<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfCacheFilter deals with page caching and action caching.
 *
 * @package    symfony
 * @subpackage filter
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfCacheFilter extends sfFilter
{
  protected
    $cacheManager = null,
    $request      = null,
    $response     = null,
    $routing      = null,
    $cache        = array();

  /**
   * Responses with its status codes may safely be kept in a shared (surrogate) cache.
   *
   * Put status codes as key in ordder to be able to use `isset()`.
   *
   * @var array
   */
  private $cacheableStatusCodes = array(
    200 => true,
    203 => true,
    300 => true,
    301 => true,
    302 => true,
    404 => true,
    410 => true,
  );

  /**
   * Initializes this Filter.
   *
   * @param sfContext $context    The current application context
   * @param array     $parameters An associative array of initialization parameters
   *
   * @return void
   *
   * @throws <b>sfInitializationException</b> If an error occurs while initializing this Filter
   */
  public function initialize($context, $parameters = array())
  {
    parent::initialize($context, $parameters);

    $this->cacheManager = $context->getViewCacheManager();
    $this->request      = $context->getRequest();
    $this->response     = $context->getResponse();
    $this->routing      = $context->getRouting();
  }

  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    // execute this filter only once, if cache is set and no GET or POST parameters
    if (!sfConfig::get('sf_cache'))
    {
      $filterChain->execute();

      return;
    }

    $exception = null;

    if ($this->executeBeforeExecution())
    {
      try
      {
        // execute next filter
        $filterChain->execute();
      }
      catch (sfStopException $exception)
      {
        if (sfView::RENDER_REDIRECTION !== $this->context->getController()->getRenderMode())
        {
          throw $exception;
        }
      }
    }

    $this->executeBeforeRendering();

    if (null !== $exception)
    {
      throw $exception;
    }
  }

  public function executeBeforeExecution()
  {
    $uri = $this->cacheManager->getCurrentCacheKey();

    if (null === $uri)
    {
      return true;
    }

    // page cache
    $cacheable = $this->cacheManager->isCacheable($uri);
    if ($cacheable && $this->cacheManager->withLayout($uri))
    {
      $inCache = $this->cacheManager->getPageCache($uri);
      $this->cache[$uri] = $inCache;

      if ($inCache)
      {
        // update the local response reference with the one pulled from the cache
        $this->response = $this->context->getResponse();

        // page is in cache, so no need to run execution filter
        return false;
      }
    }

    return true;
  }

  /**
   * Executes this filter.
   */
  public function executeBeforeRendering()
  {
    if (!$this->isCacheableResponse($this->response))
    {
      return;
    }

    $uri = $this->cacheManager->getCurrentCacheKey();

    // save page in cache
    if (isset($this->cache[$uri]) && false === $this->cache[$uri])
    {
      $this->setCacheExpiration($uri);
      $this->setCacheValidation($uri);

      // set Vary headers
      foreach ($this->cacheManager->getVary($uri, 'page') as $vary)
      {
        $this->response->addVaryHttpHeader($vary);
      }

      $this->cacheManager->setPageCache($uri);
    }

    // cache validation
    $this->checkCacheValidation();
  }

  /**
   * Sets cache expiration headers.
   *
   * @param string $uri An internal URI
   */
  protected function setCacheExpiration($uri)
  {
    // don't add cache expiration (Expires) if
    //   * the client lifetime is not set
    //   * the response already has a cache validation (Last-Modified header)
    //   * the Expires header has already been set
    if (!$lifetime = $this->cacheManager->getClientLifeTime($uri, 'page'))
    {
      return;
    }

    if ($this->response->hasHttpHeader('Last-Modified'))
    {
      return;
    }

    if (!$this->response->hasHttpHeader('Expires'))
    {
      $this->response->setHttpHeader('Expires', $this->response->getDate(time() + $lifetime), false);
      $this->response->addCacheControlHttpHeader('max-age', $lifetime);
    }
  }

  /**
   * Sets cache validation headers.
   *
   * @param string $uri An internal URI
   */

  protected function setCacheValidation($uri)
  {
    // don't add cache validation (Last-Modified) if
    //   * the client lifetime is set (cache.yml)
    //   * the response already has a Last-Modified header
    if ($this->cacheManager->getClientLifeTime($uri, 'page'))
    {
      return;
    }

    if (!$this->response->hasHttpHeader('Last-Modified'))
    {
      $this->response->setHttpHeader('Last-Modified', $this->response->getDate(time()), false);
    }

    if (sfConfig::get('sf_etag'))
    {
      $etag = '"'.md5($this->response->getContent()).'"';
      $this->response->setHttpHeader('ETag', $etag);
    }
  }

  /**
   * Checks cache validation headers.
   */
  protected function checkCacheValidation()
  {
    // Etag support
    if (sfConfig::get('sf_etag'))
    {
      $etag = '"'.md5($this->response->getContent()).'"';

      if ($this->request->getHttpHeader('IF_NONE_MATCH') == $etag)
      {
        $this->response->setStatusCode(304);
        $this->response->setHeaderOnly(true);

        if (sfConfig::get('sf_logging_enabled'))
        {
          $this->context->getEventDispatcher()->notify(new sfEvent($this, 'application.log', array('ETag matches If-None-Match (send 304)')));
        }
      }
    }

    // conditional GET support
    // never in debug mode
    if ($this->response->hasHttpHeader('Last-Modified') && (!sfConfig::get('sf_debug') || sfConfig::get('sf_test')))
    {
      $lastModified = $this->response->getHttpHeader('Last-Modified');
      if ($this->request->getHttpHeader('IF_MODIFIED_SINCE') == $lastModified)
      {
        $this->response->setStatusCode(304);
        $this->response->setHeaderOnly(true);

        if (sfConfig::get('sf_logging_enabled'))
        {
          $this->context->getEventDispatcher()->notify(new sfEvent($this, 'application.log', array('Last-Modified matches If-Modified-Since (send 304)')));
        }
      }
    }
  }

  /**
   * Returns true if the response may safely be kept in a shared (surrogate) cache.
   *
   * Responses marked "private" with an explicit Cache-Control directive are
   * considered uncacheable.
   *
   * Responses with neither a freshness lifetime (Expires, max-age) nor cache
   * validator (Last-Modified, ETag) are considered uncacheable because there is
   * no way to tell when or how to remove them from the cache.
   *
   * Note that RFC 7231 and RFC 7234 possibly allow for a more permissive implementation,
   * for example "status codes that are defined as cacheable by default [...]
   * can be reused by a cache with heuristic expiration unless otherwise indicated"
   * (https://tools.ietf.org/html/rfc7231#section-6.1)
   *
   * @param sfWebResponse $response
   *
   * @return bool
   *
   * @see https://github.com/symfony/symfony/blob/v4.1.6/src/Symfony/Component/HttpFoundation/Response.php#L523
   */
  protected function isCacheableResponse($response)
  {
    if (!$response instanceof sfWebResponse)
    {
      return false;
    }

    if (!isset($this->cacheableStatusCodes[$response->getStatusCode()]))
    {
      return false;
    }

    if ($response->isPrivate())
    {
      return false;
    }

    // Cache validation and expiration headers are always sets before save on cache.
    return true /* $this->isValidateable() || $this->isFresh() */;
  }
}
