<?php

/**
 * sfMailerSwiftmailer6 is the main entry point for the mailer system.
 *
 * This class is instanciated by sfContext on demand and compatible to swiftmailer ~6.0. It will be
 * set automagically if swiftmailer 6 is loaded.
 *
 * @see autoload.php
 *
 * @package    symfony
 * @subpackage mailer
 * @author     Thomas A. Hirsch <thomas.hirsch@vema-eg.de>
 * @version    SVN: $Id$
 */
class sfMailerSwiftmailer6 extends sfMailerBase
{
  /**
   * Sends the given message.
   *
   * @param Swift_Mime_SimpleMessage   $message         A transport instance
   * @param string[]        &$failedRecipients An array of failures by-reference
   *
   * @return int|false The number of sent emails
   */
  public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
  {
    if ($this->force)
    {
      $this->force = false;

      if (!$this->realtimeTransport->isStarted())
      {
        $this->realtimeTransport->start();
      }

      return $this->realtimeTransport->send($message, $failedRecipients);
    }

    return parent::send($message, $failedRecipients);
  }

  /**
   * @inheritDoc
   */
  public function compose($from = null, $to = null, $subject = null, $body = null)
  {
    $msg = new Swift_Message($subject);

    return $msg
      ->setFrom($from)
      ->setTo($to)
      ->setBody($body)
      ;
  }
}
