<?php

/**
 * pooling actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage pooling
 * @author     Your name here
 * @version    SVN: $Id$
 */
class poolingActions extends sfActions
{
  public function executeAddArticleButDontSave(sfWebRequest $request)
  {
    $article = new Article();
    $article->setTitle(__METHOD__.'()');

    $category = CategoryPeer::retrieveByPK($request->getParameter('category_id'));
    $category->addArticle($article);

    return sfView::NONE;
  }

  public function executeAddArticleAndSave(sfWebRequest $request)
  {
    $article = new Article();
    $article->setTitle(__METHOD__.'()');

    $category = CategoryPeer::retrieveByPK($request->getParameter('category_id'));
    $category->addArticle($article);
    $category->save();

    return sfView::NONE;
  }
}
