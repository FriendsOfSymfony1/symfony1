<?php

/**
 * sfEventLogger sends log messages to the event dispatcher to be processed
 * by registered loggers.
 *
 * @package    symfony
 * @subpackage log
 * @author     Jérôme Tamarelle <jtamarelle@groupe-exp.com>
 */
class sfEventLogger extends sfLogger
{
  /**
   * {@inheritDoc}
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    $this->dispatcher = $dispatcher;
    $this->options = $options;

    if (isset($this->options['level']))
    {
      $this->setLogLevel($this->options['level']);
    }

    // Use the default "command.log" event if not overriden
    if (!isset($this->options['event_name'])) {
      $this->options['event_name'] = 'command.log';
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function doLog($message, $priority)
  {
    $this->dispatcher->notify(new sfEvent($this, $this->options['event_name'], array($message, 'priority' => $priority)));
  }
}
