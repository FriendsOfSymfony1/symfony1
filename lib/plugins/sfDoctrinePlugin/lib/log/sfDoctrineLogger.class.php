<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Logs queries to file and web debug toolbar
 *
 * @package    symfony
 * @package    doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 * 
 * @deprecated In favor of {@link sfDoctrineConnectionProfiler}. Will be removed in symfony 1.4.
 */
class sfDoctrineLogger extends Doctrine_EventListener
{
  protected $connection = null,
            $encoding = 'UTF8', 
            $timer = null;

  public function __construct()
  {
    $this->timer = sfTimerManager::getTimer('Database (Doctrine)');
  }

  /**
   * Log a query before it is executed
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preExecute(Doctrine_Event $event)
  {
    $this->timer->startTimer();
  }

  /**
   * Add the time after a query is executed
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function postExecute(Doctrine_Event $event)
  {
    $this->sfLogQuery("executeQuery : ", $event, $this->timer->addTime());
  }

  /**
   * Add the time before a query is prepared
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function prePrepare(Doctrine_Event $event)
  {
    $this->timer->startTimer();
  }

  /**
   * Add the time after a query is prepared
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function postPrepare(Doctrine_Event $event)
  {
    $this->timer->addTime();
  }

  /**
   * Before a query statement is executed log it
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preStmtExecute(Doctrine_Event $event)
  {
    $this->timer->startTimer();
  }

  /**
   * postStmtExecute
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function postStmtExecute(Doctrine_Event $event)
  {
    $this->sfLogQuery('executeQuery : ', $event, $this->timer->addTime());
  }

  /**
   * Log a query before it is executed
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preQuery(Doctrine_Event $event)
  {
    $this->timer->startTimer();
  }

  /**
   * Post query add the time
   *
   * @param string $Doctrine_Event
   * @return void
   */
  public function postQuery(Doctrine_Event $event)
  {
    $this->sfLogQuery('executeQuery : ', $event, $this->timer->addTime());
  }

  /**
   * Log a Doctrine_Query
   *
   * @param string $message
   * @param string $event
   * @return void
   */
  protected function sfLogQuery($message, $event, $time)
  {
    $message .= $event->getQuery();

    if ($params = $event->getParams())
    {
      foreach ($params as $key => $param)
      {
        if (strlen($param) >= 255)
        {
          $len = strlen($param);
          $kb = '[' . number_format($len / 1024, 2) . 'Kb]';
          $params[$key] = $kb; 
        }
      }
      $message .= ' - ('.implode(', ', $params) . ' )';
    }
    
    $message = sprintf('{sfDoctrineLogger} [%.2f ms] %s', $time * 1000, $message);
    if (sfContext::hasInstance())
    {
      sfContext::getInstance()->getLogger()->log($message);
    }

    $sqlTimer = sfTimerManager::getTimer('Database (Doctrine)');
  }
}