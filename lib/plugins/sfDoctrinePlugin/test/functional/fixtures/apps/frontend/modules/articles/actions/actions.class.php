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
 * articles actions.
 *
 * @author     Your name here
 *
 * @version    SVN: $Id$
 */
class articlesActions extends \sfActions
{
    public function executeIndex()
    {
        $this->articleList = $this->getArticleTable()->findAll();
    }

    public function executeRedirectToShow()
    {
        $this->redirect('article', \Doctrine_Core::getTable('Article')->createQuery()->fetchOne());
    }

    public function executeShow()
    {
        $this->article = $this->getRoute()->getObject();
    }

    public function executeCreate()
    {
        $this->form = new \ArticleForm();

        $this->setTemplate('edit');
    }

    public function executeEdit($request)
    {
        $this->form = $this->getArticleForm($request->getParameter('id'));
    }

    public function executeUpdate($request)
    {
        $this->forward404Unless($request->isMethod(\sfRequest::POST));

        $this->form = $this->getArticleForm($request->getParameter('id'));

        $this->form->bind($request->getParameter('article'));
        if ($this->form->isValid()) {
            $article = $this->form->save();

            $this->redirect('articles/edit?id='.$article->get('id'));
        }

        $this->setTemplate('edit');
    }

    public function executeDelete($request)
    {
        $this->forward404Unless($article = $this->getArticleById($request->getParameter('id')));

        $article->delete();

        $this->redirect('articles/index');
    }

    private function getArticleTable()
    {
        return \Doctrine_Core::getTable('Article');
    }

    private function getArticleById($id)
    {
        return $this->getArticleTable()->find($id);
    }

    private function getArticleForm($id)
    {
        $article = $this->getArticleById($id);

        if ($article instanceof \Article) {
            return new \ArticleForm($article);
        }

        return new \ArticleForm();
    }
}
