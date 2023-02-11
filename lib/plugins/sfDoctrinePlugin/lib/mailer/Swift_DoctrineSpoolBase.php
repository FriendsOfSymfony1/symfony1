<?php

abstract class Swift_DoctrineSpoolBase extends Swift_ConfigurableSpool
{
  protected abstract function internalQueueMessage($message);
}
