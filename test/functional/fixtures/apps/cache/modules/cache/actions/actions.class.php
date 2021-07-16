<?php

/**
 * cache actions.
 *
 * @package    project
 * @subpackage cache
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class cacheActions extends CacheAppActions
{
  public function executeIndex()
  {
  }

  public function executePage()
  {
  }

  public function executeList($request)
  {
    $this->page = $request->getParameter('page', 1);
  }

  public function executeForward()
  {
    $this->forward('cache', 'page');
  }

  public function executeRedirection()
  {
    $this->redirect('/cache/page');
  }

  public function executeRedirectionWithoutLayout()
  {
    $this->redirect('/cache/page');
  }

  public function executeError404()
  {
    $this->forward404();
  }

  public function executeError404WithoutLayout()
  {
    $this->forward404();
  }

  public function executeMulti()
  {
    $this->getResponse()->setTitle('Param: '.$this->getRequestParameter('param'));
  }

  public function executeMultiBis()
  {
  }

  public function executePartial()
  {
  }

  public function executeAnotherPartial()
  {
  }

  public function executeComponent()
  {
  }

  public function executeSpecificCacheKey()
  {
  }

  public function executeAction()
  {
    $response = $this->getResponse();
    $response->setHttpHeader('symfony', 'foo');
    $response->setContentType('text/plain');
    $response->setTitle('My title');
    $response->addMeta('meta1', 'bar');
    $response->addHttpMeta('httpmeta1', 'foobar');

    sfConfig::set('ACTION_EXECUTED', true);
  }

  public function executeImageWithLayoutCacheWithLayout()
  {
    $this->prepareImage();
    $this->setLayout('image');
  }

  public function executeImageWithLayoutCacheNoLayout()
  {
    $this->prepareImage();
    $this->setLayout('image');
  }

  public function executeImageNoLayoutCacheWithLayout()
  {
    $this->prepareImage();
    $this->setLayout(false);
  }

  public function executeImageNoLayoutCacheNoLayout()
  {
    $this->prepareImage();
    $this->setLayout(false);
  }

  protected function prepareImage()
  {
    $this->getResponse()->setContentType('image/png');
    $this->image = file_get_contents(__DIR__.'/../data/ok48.png');
    $this->setTemplate('image');
  }

  public function executeLastModifiedResponse()
  {
    $this->getResponse()->setHttpHeader('Last-Modified', $this->getResponse()->getDate(sfConfig::get('LAST_MODIFIED')));
    $this->setTemplate('action');
  }

  public function executeCacheControlWithNoStoreDirective(sfWebRequest $request)
  {
    $directive = $request->getParameter('directive', 'private');

    $this->getResponse()->setHttpHeader('Cache-Control', $directive, false);

    // To support more than one directive.
    $this->getResponse()->setHttpHeader('Cache-Control', 'foo', false);

    $this->setTemplate('action');
  }

  public function executeCacheControlWithNoStoreDirectiveCacheNoLayout(sfWebRequest $request)
  {
    $this->executeCacheControlWithNoStoreDirective($request);
  }
}
