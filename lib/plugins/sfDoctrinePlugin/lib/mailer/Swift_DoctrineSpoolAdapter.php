<?php

// Defining a base class for Swift_DoctrineSpool to handle both, Swiftmailer 5 and Swiftmailer 6,
// as we are implementing a base class from the package, we can't simply remove the type hint.
if(class_exists('Swift') && version_compare(Swift::VERSION, '6.0.0') >= 0) {
  abstract class Swift_DoctrineSpoolAdapter extends Swift_DoctrineSpoolBase
  {
    public function queueMessage(Swift_Mime_SimpleMessage $message)
    {
      $this->internalQueueMessage($message);
    }
  }
} else {
  abstract class Swift_DoctrineSpoolAdapter extends Swift_DoctrineSpoolBase
  {
    public function queueMessage(Swift_Mime_Message $message)
    {
      $this->internalQueueMessage($message);
    }
  }
}
